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
    public function behaviors() { return ['verbs'=>['class'=>VerbFilter::class,'actions'=>['delete'=>['post'],'organize'=>['post']]]]; }
    public function actionIndex() { return $this->render('index', ['dataProvider'=>new ActiveDataProvider(['query'=>HomeSection::find()->orderBy(['sort_order'=>SORT_ASC,'id'=>SORT_ASC]), 'pagination'=>false])]); }
    public function actionCreate() { return $this->save(new HomeSection(['status'=>1])); }
    public function actionUpdate($id) { return $this->save($this->findModel($id)); }
    public function actionDelete($id) { $this->findModel($id)->delete(); Yii::$app->session->setFlash('success', Yii::t('app','Section deleted.')); return $this->redirect(['index']); }
    public function actionOrganize()
    {
        $items = json_decode((string) Yii::$app->request->post('items'), true);
        if (!is_array($items)) {
            throw new \yii\web\BadRequestHttpException(Yii::t('app', 'Invalid section order.'));
        }
        $models = HomeSection::find()->indexBy('id')->all();
        if (count($items) !== count($models)) {
            throw new \yii\web\BadRequestHttpException(Yii::t('app', 'Invalid section order.'));
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($items as $position => $item) {
                $id = filter_var($item['id'] ?? null, FILTER_VALIDATE_INT);
                if (!$id || !isset($models[$id])) {
                    throw new \yii\web\BadRequestHttpException(Yii::t('app', 'Invalid section order.'));
                }
                $models[$id]->updateAttributes(['sort_order'=>($position + 1) * 10, 'status'=>empty($item['enabled']) ? 0 : 1, 'updated_by'=>Yii::$app->user->id, 'updated_at'=>time()]);
                unset($models[$id]);
            }
            if ($models) throw new \yii\web\BadRequestHttpException(Yii::t('app', 'Invalid section order.'));
            $transaction->commit();
            return $this->asJson(['success'=>true]);
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
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
