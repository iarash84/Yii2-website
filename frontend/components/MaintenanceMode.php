<?php

namespace frontend\components;

use frontend\models\SystemSetting;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\Application;
use yii\web\HttpException;

class MaintenanceMode implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Event::on(Application::class, Application::EVENT_BEFORE_REQUEST, static function () use ($app) {
            try {
                $enabled = filter_var(SystemSetting::getValue('maintenance_enabled', '0'), FILTER_VALIDATE_BOOLEAN);
            } catch (\Throwable $e) {
                return;
            }
            $path = ltrim($app->request->pathInfo, '/');
            if ($enabled && $app->user->isGuest && strpos($path, 'admin') !== 0 && !preg_match('#(^|/)(login|error)$#', $path)) {
                $app->response->headers->set('Retry-After', '3600');
                throw new HttpException(503, SystemSetting::getValue('maintenance_message', Yii::t('app', 'The site is temporarily under maintenance.')));
            }
        });
    }
}
