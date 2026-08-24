<?php

namespace frontend\modules\admin\controllers;

use frontend\models\Media;
use frontend\models\Page;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PageController extends Controller
{
    public function behaviors()
    {
        return ['verbs' => ['class' => VerbFilter::class, 'actions' => ['delete' => ['post']]]];
    }

    public function actionIndex()
    {
        return $this->render('index', ['dataProvider' => new ActiveDataProvider([
            'query' => Page::find()->orderBy(['updated_at' => SORT_DESC]),
        ])]);
    }

    public function actionCreate()
    {
        return $this->save(new Page(['status' => Page::STATUS_DRAFT, 'robots' => 'index,follow']));
    }

    public function actionUpdate($id)
    {
        return $this->save($this->findModel($id));
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Page deleted.'));
        return $this->redirect(['index']);
    }

    private function save(Page $model)
    {
        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = $model->created_by ?: Yii::$app->user->id;
            $model->updated_by = Yii::$app->user->id;
            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save(false) && $model->saveTranslations(Yii::$app->request->post('translations', []))) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Page saved.'));
                        return $this->redirect(['index']);
                    }
                    $transaction->rollBack();
                } catch (\Throwable $exception) {
                    $transaction->rollBack();
                    throw $exception;
                }
            }
        }
        return $this->render($model->isNewRecord ? 'create' : 'update', [
            'model' => $model,
            'media' => Media::find()->where(['like', 'mime_type', 'image/%', false])->orderBy(['created_at' => SORT_DESC])->all(),
        ]);
    }

    private function findModel($id)
    {
        $model = Page::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        return $model;
    }
}
