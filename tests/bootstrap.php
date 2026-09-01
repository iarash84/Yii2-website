<?php

defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'test');
putenv('APP_COOKIE_VALIDATION_KEY=test-cookie-validation-key-32-bytes');
putenv('APP_DATA_ENCRYPTION_KEY=test-data-encryption-key-32-bytes');
putenv('APP_ANALYTICS_KEY=test-analytics-key-32-bytes');

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';
require $root . '/vendor/yiisoft/yii2/Yii.php';
require $root . '/common/config/bootstrap.php';
require $root . '/frontend/config/bootstrap.php';

$host = getenv('TEST_DB_HOST') ?: '127.0.0.1';
$port = getenv('TEST_DB_PORT') ?: '3306';
$database = getenv('TEST_DB_NAME') ?: 'yii2_kamancms_test';
$username = getenv('TEST_DB_USER') ?: 'root';
$password = getenv('TEST_DB_PASSWORD') !== false ? getenv('TEST_DB_PASSWORD') : '';

$config = yii\helpers\ArrayHelper::merge(
    require $root . '/common/config/main.php',
    require $root . '/frontend/config/main.php',
    [
        'id' => 'app-test',
        'components' => [
            'db' => [
                'class' => yii\db\Connection::class,
                'dsn' => "mysql:host={$host};port={$port};dbname={$database}",
                'username' => $username,
                'password' => $password,
                'charset' => 'utf8mb4',
            ],
            'request' => [
                'class' => yii\web\Request::class,
                'cookieValidationKey' => 'test-cookie-validation-key',
                'scriptFile' => $root . '/frontend/web/index.php',
                'scriptUrl' => '/index.php',
                'enableCsrfValidation' => false,
            ],
            'mailer' => [
                'class' => yii\symfonymailer\Mailer::class,
                'viewPath' => '@common/mail',
                'useFileTransport' => true,
            ],
        ],
    ]
);

$application = new yii\web\Application($config);
$application->request->setUrl('/');
