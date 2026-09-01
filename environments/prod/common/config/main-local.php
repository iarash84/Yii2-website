<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => sprintf('mysql:host=%s;port=%s;dbname=%s', getenv('DB_HOST') ?: '127.0.0.1', getenv('DB_PORT') ?: '3306', getenv('DB_NAME') ?: 'yii2_website'),
            'username' => getenv('DB_USER') ?: 'root',
            'password' => getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '',
            'charset' => 'utf8mb4',
        ],
        'mailer' => [
            'class' => 'yii\symfonymailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => filter_var(getenv('MAIL_USE_FILE_TRANSPORT') ?: '0', FILTER_VALIDATE_BOOLEAN),
        ],
    ],
];
