<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'savePath' => '@runtime',
            'cookieParams' => [
                'httpOnly' => true,
                'sameSite' => 'Lax',
                'secure' => YII_ENV_PROD,
            ],
        ],
    ],
];
