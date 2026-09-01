<?php

namespace frontend\components;

use Yii;
use yii\web\TooManyRequestsHttpException;

class PublicRateLimiter
{
    public static function enforce($scope, $limit = 5, $period = 600)
    {
        $ip = (string) Yii::$app->request->userIP;
        $key = 'public-rate:' . hash('sha256', $scope . '|' . $ip);
        $mutexKey = 'rate-limit-' . hash('sha256', $key);
        if (!Yii::$app->mutex->acquire($mutexKey, 3)) {
            throw new TooManyRequestsHttpException(Yii::t('app', 'Too many requests. Please try again later.'));
        }
        try {
            $count = (int) Yii::$app->cache->get($key);
            if ($count >= $limit) {
                throw new TooManyRequestsHttpException(Yii::t('app', 'Too many requests. Please try again later.'));
            }
            Yii::$app->cache->set($key, $count + 1, $period);
        } finally {
            Yii::$app->mutex->release($mutexKey);
        }
    }
}
