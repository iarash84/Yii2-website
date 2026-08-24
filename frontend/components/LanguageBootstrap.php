<?php

namespace frontend\components;

use yii\base\BootstrapInterface;

class LanguageBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $manager = $app->languageManager;
        try {
            $path = trim($app->request->getPathInfo(), '/');
        } catch (\yii\base\InvalidConfigException $exception) {
            $path = '';
        }
        $firstSegment = $path === '' ? null : explode('/', $path, 2)[0];

        if ($firstSegment === 'admin') {
            $manager->activate($manager->adminLanguage);
            return;
        }

        $manager->activate($manager->normalize($firstSegment) ?: $manager->defaultLanguage);
    }
}
