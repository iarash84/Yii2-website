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
    'language' => 'fa_IR',
    'sourceLanguage' => 'en_US',
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'admin' => [
            'class' => 'frontend\modules\admin\Module',
        ],
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en_US',
                ],
            ],
        ],
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'admin' => 'admin/setting/index',
                'admin/blog' => 'admin/blog/index',
                'admin/users' => 'admin/user/index',
                'admin/settings' => 'admin/setting/index',
                'admin/<controller:[\w-]+>/<action:[\w-]+>' => 'admin/<controller>/<action>',
                'admin/<controller:[\w-]+>' => 'admin/<controller>/index',
                'blog' => 'blog/index',
                'changepass' => 'admin/user/change',
                '<alias:index|contact|about|login|logout|faqs|order|sample|opportunity>' => 'site/<alias>',
                '<controller:\w+>s' => '<controller>/index',
                '<controller:\w+>/<id:\d+>/<action:(update|delete)>' => '<controller>/<action>',
                '<controller:\w+>/<id:\d+>/<subject>/' => '<controller>/view',
                '<controller:\w+>/<id:\d+>' => '<controller>/view'
            ],
        ],

    ],
    'params' => $params,
];

