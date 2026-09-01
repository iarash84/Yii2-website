<?php

namespace frontend\modules\admin\controllers;

use frontend\services\BackupService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\UploadedFile;

class BackupController extends Controller
{
    public function behaviors()
    {
        return ['verbs' => ['class' => VerbFilter::class,'actions' => ['create' => ['post'],'restore' => ['post']]]];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionCreate()
    {
        return Yii::$app->response->sendContentAsFile(BackupService::create(), 'database-backup-' . date('Ymd-His') . '.json', ['mimeType' => 'application/json']);
    }
    public function actionRestore()
    {
        $file = UploadedFile::getInstanceByName('backupFile');
        if (!$file || $file->size > 50 * 1024 * 1024 || strtolower($file->extension) !== 'json') {
            throw new \yii\web\BadRequestHttpException(Yii::t('app', 'Select a valid backup file.'));
        } BackupService::restore(file_get_contents($file->tempName));
        Yii::$app->session->setFlash('success', Yii::t('app', 'Backup restored successfully.'));
        return $this->redirect(['index']);
    }
}
