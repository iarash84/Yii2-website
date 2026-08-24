<?php

namespace frontend\components;

use Yii;
use yii\web\UrlManager;

class LocalizedUrlManager extends UrlManager
{
    public function createUrl($params)
    {
        if (is_array($params) && isset($params[0])) {
            $route = ltrim((string) $params[0], '/');
            $isAdmin = $route === 'admin' || strpos($route, 'admin/') === 0;
            if (!$isAdmin && !isset($params['language']) && Yii::$app->has('languageManager')) {
                $params['language'] = Yii::$app->languageManager->getActiveLanguage();
            }
        }
        return parent::createUrl($params);
    }
}
