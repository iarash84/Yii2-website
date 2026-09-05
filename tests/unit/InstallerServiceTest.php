<?php

namespace tests\unit;

use common\installer\InstallerService;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class InstallerServiceTest extends TestCase
{
    private string $root;

    protected function setUp(): void
    {
        $this->root = sys_get_temp_dir() . '/yii-installer-' . bin2hex(random_bytes(6));
        foreach (['frontend/runtime', 'console/runtime', 'frontend/web/assets', 'frontend/web/upload', 'storage/resumes'] as $directory) {
            mkdir($this->root . '/' . $directory, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->root, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }
        rmdir($this->root);
    }

    public function testEnvironmentAndLockFilesDoNotContainAdministratorPassword(): void
    {
        $installer = new InstallerService($this->root);
        $installer->writeEnvironment(
            ['host' => '127.0.0.1', 'port' => 3306, 'name' => 'website', 'user' => 'root', 'password' => 'db"secret'],
            ['language' => 'fa', 'admin_language' => 'fa', 'url' => 'https://example.test']
        );
        $environment = file_get_contents($this->root . '/.env');
        self::assertStringContainsString('DB_PASSWORD="db\\"secret"', $environment);
        self::assertStringNotContainsString('ADMIN_PASSWORD', $environment);
        preg_match_all('/^APP_(?:COOKIE_VALIDATION|DATA_ENCRYPTION|ANALYTICS)_KEY="([a-f0-9]{64})"$/m', $environment, $matches);
        self::assertCount(3, $matches[1]);
        self::assertCount(3, array_unique($matches[1]));
        self::assertFalse($installer->isInstalled());
        $installer->lock(['language' => 'fa']);
        self::assertTrue($installer->isInstalled());
        self::assertStringNotContainsString('secret', file_get_contents($installer->lockPath()));
    }

    public function testEnvironmentAndLockPathsCanBeStoredOutsideProjectRoot(): void
    {
        $configurationDirectory = $this->root . '/persistent/config';
        mkdir($configurationDirectory, 0777, true);
        $environmentFile = $configurationDirectory . '/application.env';
        $lockFile = $configurationDirectory . '/installation.lock';
        putenv('APP_ENV_FILE=' . $environmentFile);
        putenv('INSTALL_LOCK_FILE=' . $lockFile);

        try {
            $installer = new InstallerService($this->root);
            self::assertSame($environmentFile, getenv('APP_ENV_FILE'));
            self::assertSame($lockFile, $installer->lockPath());
            $installer->writeEnvironment(
                ['host' => 'db', 'port' => 3306, 'name' => 'website', 'user' => 'app', 'password' => 'secret'],
                ['language' => 'fa', 'url' => 'https://example.test']
            );
            $installer->lock(['language' => 'fa']);
            clearstatcache(true, $environmentFile);
            clearstatcache(true, $lockFile);

            self::assertFileExists($environmentFile);
            self::assertTrue($installer->isInstalled());
            self::assertTrue($installer->requirements()['Writable: project configuration']);
        } finally {
            putenv('APP_ENV_FILE');
            putenv('INSTALL_LOCK_FILE');
        }
    }

    public function testAdministratorPasswordPolicyIsEnforced(): void
    {
        $installer = new InstallerService($this->root);
        $this->expectException(RuntimeException::class);
        $installer->validateAdministrator(['username' => 'admin', 'email' => 'admin@example.test', 'password' => 'weak']);
    }

    public function testDatabaseConfigurationRejectsDsnInjectionAndInvalidPorts(): void
    {
        $installer = new InstallerService($this->root);
        $this->expectException(RuntimeException::class);
        $installer->validateDatabase([
            'host' => '127.0.0.1;dbname=other',
            'port' => 70000,
            'name' => 'website',
            'user' => 'root',
        ]);
    }
    public function testPhpCliBinaryIsDetectedWithoutUsingWebServerBinary(): void
    {
        $installer = new InstallerService($this->root);
        $binary = $installer->phpCliBinary();

        self::assertNotNull($binary);
        self::assertFileExists($binary);
        self::assertMatchesRegularExpression('/^php(?:\.exe)?$/i', basename($binary));
    }
}
