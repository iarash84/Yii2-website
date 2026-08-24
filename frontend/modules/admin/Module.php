<?php

namespace frontend\modules\admin;

use Yii;
use yii\web\ForbiddenHttpException;

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

        $permission = $this->requiredPermission($action->controller->id, $action->id);
        if (!Yii::$app->user->can('accessAdmin') || !Yii::$app->user->can($permission)) {
            throw new ForbiddenHttpException(Yii::t('app', 'Forbidden Http Exception'));
        }

        return parent::beforeAction($action);
    }

    private function requiredPermission($controllerId, $actionId)
    {
        if ($controllerId === 'user' && $actionId === 'change') {
            return 'accessAdmin';
        }

        if ($controllerId === 'user') {
            return 'manageUsers';
        }

        if (in_array($controllerId, ['blog', 'category', 'carousel', 'sample'], true)) {
            return 'manageContent';
        }

        if (in_array($controllerId, ['contact', 'order', 'opportunity'], true)) {
            return 'viewSubmissions';
        }

        return 'manageSettings';
    }
}
