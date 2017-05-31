<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    //'language' => 'fa_IR',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'DateTimeConverter' => [
            'class' => 'app\Components\DateTimeConverter',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'changepass' => 'user/change',
                '<alias:index|contact|about|login|logout|faqs|order|sample|opportunity|blog>' => 'site/<alias>',
                '<alias:social|system>' => 'setting/<alias>',
                '<alias:log>' => 'user/<alias>',
                '<controller:\w+>s' => '<controller>/index',
                '<controller:\w+>/<id:\d+>/<action:(update|delete)>' => '<controller>/<action>',
                '<controller:\w+>/<id:\d+>/<subject>/' => '<controller>/view',
                '<controller:\w+>/<id:\d+>' => '<controller>/view'
            ],
        ],

    ],
    'params' => $params,
];

