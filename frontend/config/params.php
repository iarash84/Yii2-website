<?php
return [
    'defaultLanguage' => getenv('APP_LANGUAGE') ?: 'fa',
    'adminLanguage' => getenv('ADMIN_LANGUAGE') ?: 'fa',
    'languages' => [
        'fa' => [
            'locale' => 'fa-IR',
            'yii' => 'fa_IR',
            'label' => 'فارسی',
            'direction' => 'rtl',
            'fallback' => 'en',
        ],
        'en' => [
            'locale' => 'en-US',
            'yii' => 'en_US',
            'label' => 'English',
            'direction' => 'ltr',
            'fallback' => 'fa',
        ],
    ],
];
