<?php

namespace frontend\modules\admin;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\admin\controllers';
    public $defaultRoute = 'setting/index';

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->setReturnUrl(Yii::$app->request->url);
            Yii::$app->response->redirect(['/site/login']);
            return false;
        }

        return parent::beforeAction($action);
    }
}
