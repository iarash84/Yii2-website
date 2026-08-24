<?php

namespace frontend\modules\admin\controllers;

use frontend\models\Blog;
use frontend\models\Contact;
use frontend\models\Opportunity;
use frontend\models\Order;
use frontend\models\Sample;
use Yii;
use yii\web\Controller;

class DashboardController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', [
            'counts' => [
                'posts' => Blog::find()->count(),
                'samples' => Sample::find()->count(),
                'contacts' => Yii::$app->user->can('viewSubmissions') ? Contact::find()->count() : null,
                'orders' => Yii::$app->user->can('viewSubmissions') ? Order::find()->count() : null,
                'opportunities' => Yii::$app->user->can('viewSubmissions') ? Opportunity::find()->count() : null,
            ],
        ]);
    }
}
