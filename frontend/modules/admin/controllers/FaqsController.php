<?php

namespace frontend\modules\admin\controllers;

use frontend\models\Faqs;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class FaqsController extends Controller
{
    public function behaviors()
    {
        return ['verbs' => ['class' => VerbFilter::class,'actions' => ['delete' => ['post']]]];
    }
    public function actionIndex()
    {
        return $this->render('index', ['dataProvider' => new ActiveDataProvider(['query' => Faqs::find()->orderBy(['sort_order' => SORT_ASC])])]);
    }
    public function actionCreate()
    {
        return $this->save(new Faqs(['status' => 1]));
    }
    public function actionUpdate($id)
    {
        return $this->save($this->find($id));
    }
    public function actionDelete($id)
    {
        $this->find($id)->delete();
        return $this->redirect(['index']);
    }
    private function save(Faqs $model)
    {
        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = $model->user_id ?: Yii::$app->user->id;
            if ($model->save() && $model->saveTranslations(Yii::$app->request->post('translations', []))) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'FAQ saved.'));
                return $this->redirect(['index']);
            }
        }return $this->render($model->isNewRecord ? 'create' : 'update', ['model' => $model]);
    }
    private function find($id)
    {
        if (($model = Faqs::findOne($id)) !== null) {
            return $model;
        }throw new NotFoundHttpException();
    }
}
