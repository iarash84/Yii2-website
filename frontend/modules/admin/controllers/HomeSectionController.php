<?php

namespace frontend\modules\admin\controllers;

use frontend\models\HomeSection;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class HomeSectionController extends Controller
{
    public function behaviors() { return ['verbs'=>['class'=>VerbFilter::class,'actions'=>['delete'=>['post']]]]; }
    public function actionIndex() { return $this->render('index', ['dataProvider'=>new ActiveDataProvider(['query'=>HomeSection::find()->orderBy(['sort_order'=>SORT_ASC,'id'=>SORT_ASC])])]); }
    public function actionCreate() { return $this->save(new HomeSection(['status'=>1])); }
    public function actionUpdate($id) { return $this->save($this->findModel($id)); }
    public function actionDelete($id) { $this->findModel($id)->delete(); Yii::$app->session->setFlash('success', Yii::t('app','Section deleted.')); return $this->redirect(['index']); }
    private function save(HomeSection $model)
    {
        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = $model->created_by ?: Yii::$app->user->id;
            $model->updated_by = Yii::$app->user->id;
            if ($model->save() && $model->saveTranslations(Yii::$app->request->post('translations', []))) {
                Yii::$app->session->setFlash('success', Yii::t('app','Home section saved.'));
                return $this->redirect(['index']);
            }
        }
        return $this->render($model->isNewRecord ? 'create' : 'update', ['model'=>$model]);
    }
    private function findModel($id) { if (($model=HomeSection::findOne($id)) !== null) return $model; throw new NotFoundHttpException(); }
}
