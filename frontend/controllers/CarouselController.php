<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Carousel;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CarouselController implements the CRUD actions for Carousel model.
 */
class CarouselController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Carousel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Carousel::find()->orderBy('order_num'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Carousel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Carousel;

        if ($model->load(Yii::$app->request->post())) {


            $model->image = UploadedFile::getInstance($model, 'image');

            if (!empty($model->image)) {
                // generate a unique file name
                $file_upload_path = 'upload/image/' . Yii::$app->security->generateRandomString() . "." . $model->image->extension;
                $model->image->saveAs($file_upload_path);
                $model->image =  $file_upload_path;
                $model->status = "1";
                $model->user_id = Yii::$app->user->identity->getId();
                $maxOrder = Carousel::find()->select('MAX(`order_num`)')->scalar();
                $model->order_num = ++$maxOrder;

                $model->save();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Carousel created'));
                return $this->redirect(['index']);

            }

            return $this->refresh();
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * Updates an existing Carousel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $image = $model->image;
        if ($model->load(Yii::$app->request->post())) {

            if (!empty(UploadedFile::getInstance($model, 'image'))) {
                if(!empty($image))
                    unlink($image);

                $model->image = UploadedFile::getInstance($model, 'image');


                $file_upload_path = 'upload/image/' . Yii::$app->security->generateRandomString() . "." . $model->image->extension;
                $model->image->saveAs($file_upload_path);
                $model->image =  $file_upload_path;
            }else{
                $model->image = $image;
            }

            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Carousel updated Successfully'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionUp($id)
    {
        return $this->move($id, 'up');
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDown($id)
    {
        return $this->move($id, 'down');
    }

    /**
     * @param $id
     * @param $direction
     * @return \yii\web\Response
     */
    private function move($id, $direction)
    {

        if (($model = Carousel::findOne($id))) {
            if ($direction === 'up') {
                $eq = '<';
                $orderDir = 'DESC';
            } else {
                $eq = '>';
                $orderDir = 'ASC';
            }

            $query = Carousel::find()->orderBy('order_num ' . $orderDir)->limit(1);

            $where = [$eq, 'order_num', $model->order_num];

            $modelSwap = $query->where($where)->one();

            if (!empty($modelSwap)) {

                $newOrderNum = $modelSwap->order_num;

                $modelSwap->order_num = $model->order_num;
                $modelSwap->save();

                $model->order_num = $newOrderNum;
                $model->save();
            }
        }
        return $this->redirect(['index']);
    }


    /**
     * Deletes an existing Carousel model.
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

        return $this->redirect(['index']);
    }

    /**
     * Finds the Carousel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Carousel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Carousel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
