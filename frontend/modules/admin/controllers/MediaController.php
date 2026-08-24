<?php

namespace frontend\modules\admin\controllers;

use common\components\SecureUpload;
use frontend\models\Media;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class MediaController extends Controller
{
    public function behaviors()
    {
        return ['verbs' => ['class' => VerbFilter::class, 'actions' => ['delete' => ['post']]]];
    }

    public function actionIndex()
    {
        $model = new Media();
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName('mediaFile');
            if ($file === null) {
                $model->addError('path', Yii::t('app', 'Select a file to upload.'));
            } else {
                $path = SecureUpload::storeMedia($file);
                $absolutePath = Yii::getAlias('@webroot/' . $path);
                $model->setAttributes([
                    'path' => $path, 'original_name' => basename(FileHelper::normalizePath($file->name)),
                    'mime_type' => FileHelper::getMimeType($absolutePath), 'extension' => pathinfo($path, PATHINFO_EXTENSION),
                    'size' => $file->size, 'alt_text' => Yii::$app->request->post('altText'),
                    'created_by' => Yii::$app->user->id,
                ], false);
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Media uploaded.'));
                    return $this->redirect(['index']);
                }
                @unlink(Yii::getAlias('@webroot/' . $path));
            }
        }
        return $this->render('index', [
            'model' => $model,
            'dataProvider' => new ActiveDataProvider(['query' => Media::find()->orderBy(['created_at' => SORT_DESC])]),
        ]);
    }

    public function actionDelete($id)
    {
        $model = Media::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        if ($model->delete()) {
            $path = Yii::getAlias('@webroot/' . ltrim($model->path, '/'));
            if (is_file($path)) {
                unlink($path);
            }
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'Media deleted.'));
        return $this->redirect(['index']);
    }
}
