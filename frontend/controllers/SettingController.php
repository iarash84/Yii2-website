<?php

namespace frontend\controllers;


use frontend\models\Footer;

use frontend\models\HtmlForm;
use frontend\models\SocialForm;
use Yii;
use frontend\models\Setting;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\Controller;

/**
 * SettingController implements the CRUD actions for Setting model.
 */
class SettingController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'about', 'home', 'social'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Footer();
        $setting = new Setting();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $setting->address = $model->address;
            $setting->email = $model->email;
            $setting->phoneNumber = $model->phoneNumber;
            $setting->faxNumber = $model->faxNumber;
            $setting->postalCode = $model->postalCode;
            $setting->workingHours = $model->workingHours;
            $setting->companyName = $model->companyName;
            Yii::$app->session->setFlash('success', Yii::t('app', 'Thank you! Update successfully completed!'));

        } else {
            $model->address = $setting->address;
            $model->email = $setting->email;
            $model->phoneNumber = $setting->phoneNumber;
            $model->faxNumber = $setting->faxNumber;
            $model->postalCode = $setting->postalCode;
            $model->workingHours = $setting->workingHours;
            $model->companyName = $setting->companyName;
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Setting model.
     * @return string|\yii\web\Response
     */
    public function actionAbout()
    {
        if (($model = Setting::find()->where(['type' => 'About'])->one()) == null)
            $model = new Setting();

        if ($model->load(Yii::$app->request->post())) {

            $model->user_id = Yii::$app->user->identity->getId();
            $model->type = 'About';
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Thank you! Update successfully completed!'));

            return $this->redirect(['setting/about']);
        } else {
            return $this->render('about', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return string
     */
    public function actionSocial()
    {
        $model = new SocialForm();
        $setting = new Setting();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $setting->facebook = $model->facebook;
            $setting->twitter = $model->twitter;
            $setting->linkedin = $model->linkedin;
            $setting->aparat = $model->aparat;
            $setting->youtube = $model->youtube;
            $setting->instagram = $model->instagram;
            $setting->telegram = $model->telegram;
            $setting->googlePlus = $model->googlePlus;

            Yii::$app->session->setFlash('success', Yii::t('app', 'Thank you! Update successfully completed!'));

        } else {
            $model->facebook = $setting->facebook;
            $model->twitter = $setting->twitter;
            $model->linkedin = $setting->linkedin;
            $model->aparat = $setting->aparat;
            $model->youtube = $setting->youtube;
            $model->instagram = $setting->instagram;
            $model->telegram = $setting->telegram;
            $model->googlePlus = $setting->googlePlus;
        }

        return $this->render('social', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     */
    public function actionHome()
    {

        $model = new HtmlForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            file_put_contents("upload/html/home", $model->pageContent);
        } else {
            $model->pageName = "home";
            if (file_exists("upload/html/home"))
                $model->pageContent = file_get_contents("upload/html/home");
        }

        return $this->render('home', [
            'model' => $model,
        ]);
    }


    public function actionSystem()
    {
        return $this->render('system');
    }


    public function actionFlush()
    {
        Yii::$app->cache->flush();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Cache flushed'));
        return $this->render('system');
    }

    public function actionClear()
    {
        foreach(glob(Yii::$app->assetManager->basePath . DIRECTORY_SEPARATOR . '*') as $asset){
            if(is_link($asset)){
                unlink($asset);
            } elseif(is_dir($asset)){
                FileHelper::removeDirectory($asset);
            } else {
                unlink($asset);
            }
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'Assets cleared'));
        return $this->render('system');
    }


}
