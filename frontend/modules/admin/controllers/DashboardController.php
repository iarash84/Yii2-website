<?php

namespace frontend\modules\admin\controllers;

use frontend\models\Blog;
use frontend\models\Contact;
use frontend\models\Opportunity;
use frontend\models\Order;
use frontend\models\Sample;
use frontend\models\Faqs;
use frontend\models\Media;
use frontend\models\Page;
use frontend\models\SystemSetting;
use common\models\User;
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
                'pages' => Yii::$app->user->can('managePages') ? Page::find()->count() : null,
                'media' => Yii::$app->user->can('manageMedia') ? Media::find()->count() : null,
                'faqs' => Yii::$app->user->can('manageContent') ? Faqs::find()->count() : null,
                'users' => Yii::$app->user->can('manageUsers') ? User::find()->count() : null,
            ],
            'latestPosts' => Blog::find()->with(['category', 'user'])->orderBy(['createDatetime' => SORT_DESC, 'id' => SORT_DESC])->limit(5)->all(),
            'latestContacts' => Yii::$app->user->can('viewSubmissions') ? Contact::find()->orderBy(['createDateTime' => SORT_DESC, 'id' => SORT_DESC])->limit(5)->all() : [],
            'systemStatus' => [
                'maintenance' => filter_var(SystemSetting::getValue('maintenance_enabled', '0'), FILTER_VALIDATE_BOOLEAN),
                'calendar' => SystemSetting::getValue('date_calendar', 'gregorian'),
                'environment' => YII_ENV,
            ],
        ]);
    }
}
