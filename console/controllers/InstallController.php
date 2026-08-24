<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class InstallController extends Controller
{
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
}
