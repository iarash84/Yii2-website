<?php
namespace frontend\controllers;

use app\models\Faqs;

use frontend\models\Opportunity;
use frontend\models\OpportunityForm;
use frontend\models\OrderForm;
use frontend\models\Sample;
use frontend\models\Setting;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Displays order page.
     *
     * @return mixed
     */
    public function actionOrder()
    {
        $model = new OrderForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveOrder()) {
                Yii::$app->session->setFlash('success', Yii::t('app','Thank you for your order. We will respond to you as soon as possible.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app','There was an error when adding your contact.'));
            }

            return $this->refresh();
        } else {
            return $this->render('order', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return string
     */
    public function actionSample(){

        $dataProvider = new ActiveDataProvider([
            'query' => Sample::find(),
        ]);

        return $this->render('sample' , [
            'dataProvider' => $dataProvider,
        ] );
    }

    /**
     * Displays opportunity page.
     *
     * @return mixed
     */
    public function actionOpportunity()
    {
        $model = new OpportunityForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $opportunity= new Opportunity();
            $model->resume = UploadedFile::getInstance($model, 'resume');

            if (!empty($model->resume)) {

                // generate a unique file name
                $file_upload_path = 'upload/resume/' . Yii::$app->security->generateRandomString() . "." . $model->resume->extension;
                $model->resume->saveAs($file_upload_path);
                $opportunity->resume = $file_upload_path;
            }

            $opportunity->name = $model->name;
            $opportunity->email = $model->email;
            $opportunity->phoneNumber = $model->phoneNumber;
            $opportunity->save();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Your resume uploaded successfully. We will respond to you as soon as possible.'));

            return $this->refresh();
        } else {

            if(Yii::$app->user->isGuest) {

                return $this->render('opportunity', [
                    'model' => $model,
                ]);
            }else
            {
                return $this->redirect(['opportunity/index']);
            }
        }
    }

    /**
     * Displays FAQS page.
     *
     * @return string
     */
    public function actionFaqs()
    {
        $models = Faqs::find()->all();
        return $this->render('faqs', ['models' => $models]);
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveContact()) {
                Yii::$app->session->setFlash('success', Yii::t('app','Thank you for contacting us. We will respond to you as soon as possible.'));
            } else {
                Yii::$app->session->setFlash('error', 'There was an error when adding your contact.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout(){
        if (Yii::$app->user->isGuest) {
            $model = Setting::find()->where(['type' => 'About'])->one();
            return $this->render('about',[
            'model' => $model,
        ]);
        } else {
            return $this->redirect(['setting/about']);
        }
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset(){

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
