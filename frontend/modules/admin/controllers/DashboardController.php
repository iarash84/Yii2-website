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
use frontend\models\VisitorReport;
use common\models\User;
use Yii;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use frontend\models\DashboardPreference;

class DashboardController extends Controller
{
    public function behaviors() { return ['verbs'=>['class'=>VerbFilter::class,'actions'=>['layout'=>['post']]]]; }
    public function actionIndex()
    {
        $analytics = Yii::$app->user->can('viewAnalytics') ? VisitorReport::dashboard(30) : null;
        return $this->render('index', [
            'dashboardLayout' => DashboardPreference::layoutFor(Yii::$app->user->id),
            'analytics' => $analytics,
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
            'latestPosts' => Blog::find()->with(['category', 'user'])->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC])->limit(5)->all(),
            'latestContacts' => Yii::$app->user->can('viewSubmissions') ? Contact::find()->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC])->limit(5)->all() : [],
            'systemStatus' => [
                'maintenance' => filter_var(SystemSetting::getValue('maintenance_enabled', '0'), FILTER_VALIDATE_BOOLEAN),
                'calendar' => SystemSetting::getValue('date_calendar', 'gregorian'),
                'environment' => YII_ENV,
            ],
        ]);
    }

    public function actionLayout()
    {
        $decoded = json_decode((string) Yii::$app->request->post('layout'), true);
        if (!is_array($decoded)) throw new BadRequestHttpException(Yii::t('app', 'Invalid dashboard layout.'));
        $layout = DashboardPreference::normalize($decoded);
        $model = DashboardPreference::findOne(Yii::$app->user->id) ?: new DashboardPreference(['user_id'=>Yii::$app->user->id]);
        $model->layout_json = json_encode($layout, JSON_UNESCAPED_SLASHES);
        $model->updated_at = time();
        if (!$model->save()) throw new BadRequestHttpException(implode(' ', $model->getFirstErrors()));
        return $this->asJson(['success'=>true, 'layout'=>$layout]);
    }
}
