<?php

namespace console\controllers;

use common\installer\InstallerService;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class InstallController extends Controller
{
    public $force = false;

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), ['force']);
    }

    public function actionIndex()
    {
        $installer = new InstallerService();
        if ($installer->isInstalled()) {
            if (!$this->force || !$this->interactive || !$this->confirm('Installation is locked. Do you want to run it again?')) {
                $this->stderr("Installation is locked.\n");
                return ExitCode::NOPERM;
            }
        }
        foreach ($installer->requirements() as $label => $passed) {
            $this->stdout(($passed ? '[OK] ' : '[ERROR] ') . $label . "\n");
        }
        if (!$installer->isReady()) {
            return ExitCode::CONFIG;
        }
        $database = [
            'host' => $this->readValue('DB_HOST', 'Database host', '127.0.0.1'),
            'port' => $this->readValue('DB_PORT', 'Database port', '3306'),
            'name' => $this->readValue('DB_NAME', 'Database name', 'yii2_kamancms'),
            'user' => $this->readValue('DB_USER', 'Database user', 'root'),
            'password' => $this->readSecret('DB_PASSWORD', 'Database password'),
        ];
        $admin = [
            'username' => $this->readValue('ADMIN_USERNAME', 'Administrator username', 'admin'),
            'email' => $this->readValue('ADMIN_EMAIL', 'Administrator email', 'admin@example.com'),
            'password' => $this->readSecret('ADMIN_PASSWORD', 'Administrator password'),
        ];
        $site = [
            'name' => $this->readValue('SITE_NAME', 'Site name', 'My website'),
            'address' => $this->readValue('SITE_ADDRESS', 'Site address', ''),
            'url' => $this->readValue('APP_URL', 'Site URL', 'http://127.0.0.1:8080'),
            'language' => $this->readValue('APP_LANGUAGE', 'Site language (fa/en)', 'fa'),
            'admin_language' => $this->readValue('ADMIN_LANGUAGE', 'Admin language (fa/en)', 'fa'),
        ];
        try {
            $this->stdout("Testing database connection...\n");
            $installer->ensureDatabase($database);
            $this->stdout($installer->migrate($database) . "\n");
            $this->stdout($installer->createAdministrator($database, $admin) . "\n");
            $installer->configureSite($database, $site, $admin);
            $installer->writeEnvironment($database, $site);
            $installer->lock($site);
            $this->stdout("Installation completed. Open /site/login to sign in.\n");
            return ExitCode::OK;
        } catch (\Throwable $exception) {
            $this->stderr('Installation failed: ' . $exception->getMessage() . "\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    public function actionCheck()
    {
        $errors = [];
        foreach (['DB_HOST', 'DB_NAME', 'DB_USER'] as $variable) {
            if (getenv($variable) === false || getenv($variable) === '') {
                $errors[] = "Environment variable {$variable} is not configured.";
            }
        }
        foreach (
            [
            Yii::getAlias('@frontend/runtime'),
            Yii::getAlias('@frontend/web/assets'),
            Yii::getAlias('@frontend/web/upload'),
            Yii::getAlias('@storage/resumes'),
            ] as $directory
        ) {
            if (!is_dir($directory) || !is_writable($directory)) {
                $errors[] = "Directory is not writable: {$directory}";
            }
        }
        try {
            Yii::$app->db->open();
            Yii::$app->db->createCommand('SELECT 1')->queryScalar();
        } catch (\Throwable $exception) {
            $errors[] = 'Database connection failed: ' . $exception->getMessage();
        }
        if ($errors) {
            foreach ($errors as $error) {
                $this->stderr("[ERROR] {$error}\n");
            }
            return ExitCode::CONFIG;
        }
        $this->stdout("Environment, database and writable directories are ready.\n");
        return ExitCode::OK;
    }

    private function readValue(string $environment, string $label, string $default): string
    {
        $value = getenv($environment);
        if ($value !== false && $value !== '') {
            return (string) $value;
        }
        return $this->interactive ? (string) $this->prompt($label, ['default' => $default]) : $default;
    }

    private function readSecret(string $environment, string $label): string
    {
        $value = getenv($environment);
        if ($value !== false) {
            return (string) $value;
        }
        return $this->interactive ? (string) $this->prompt($label) : '';
    }
}
