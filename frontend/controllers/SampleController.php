<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Sample;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * SampleController implements the CRUD actions for Sample model.
 */
class SampleController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [ 'create','update','delete' ],
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
     * Creates a new Sample model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sample();
        $model->image = UploadedFile::getInstance($model, 'image');

        if (!empty($model->image)) {

            $file_upload_path = 'upload/image/' . Yii::$app->security->generateRandomString() . "." . $model->image->extension;
            $model->image->saveAs($file_upload_path);
            $model->user_id = Yii::$app->user->identity->getId();
            if ($model->load(Yii::$app->request->post())) {

                $model->image = $file_upload_path;
                $model->save();
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'sample created'));
            return $this->redirect(['site/sample']);
        } else {

            return $this->render('create', [
                'model' => $model,
            ]);
        }


    }

    /**
     * Updates an existing Sample model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $image = $model->image;

        if ($model->load(Yii::$app->request->post())) {

            $uploadedFile = UploadedFile::getInstance($model, 'image');

            if (!empty($uploadedFile)) {
                $model->image = $uploadedFile;

                if (!empty($image) && file_exists($image))
                    unlink($image);

                $file_upload_path = 'upload/image/' . Yii::$app->security->generateRandomString() . "." . $model->image->extension;
                $model->image->saveAs($file_upload_path);

                $model->image = $file_upload_path;
            }else{
                $model->image = $image;
            }

            $model->user_id = Yii::$app->user->identity->getId();
            $model->save();
            return $this->redirect(['site/sample']);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing Sample model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!empty($model->image) && file_exists('upload/image/' . basename($model->image)))
            unlink('upload/image/' . basename($model->image));
        $model->delete();

        return $this->redirect(['site/sample']);
    }

    /**
     * Finds the Sample model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sample the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sample::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
