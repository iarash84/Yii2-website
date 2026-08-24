<?php

namespace frontend\modules\admin\controllers;

use frontend\models\MenuItem;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MenuController extends Controller
{
    public function behaviors()
    {
        return ['verbs' => ['class' => VerbFilter::class, 'actions' => ['delete' => ['post']]]];
    }

    public function actionIndex()
    {
        return $this->render('index', ['dataProvider' => new ActiveDataProvider([
            'query' => MenuItem::find()->orderBy(['location' => SORT_ASC, 'sort_order' => SORT_ASC, 'id' => SORT_ASC]),
            'pagination' => false,
        ])]);
    }

    public function actionCreate()
    {
        $model = new MenuItem(['status' => 1, 'target' => '_self', 'location' => 'main']);
        return $this->save($model);
    }

    public function actionUpdate($id)
    {
        return $this->save($this->findModel($id));
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Menu item deleted.'));
        return $this->redirect(['index']);
    }

    private function save(MenuItem $model)
    {
        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = $model->created_by ?: Yii::$app->user->id;
            if ($model->parent_id == $model->id) {
                $model->addError('parent_id', Yii::t('app', 'An item cannot be its own parent.'));
            } elseif ($model->save() && $model->saveTranslations(Yii::$app->request->post('translations', []))) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Menu item saved.'));
                return $this->redirect(['index']);
            }
        }
        $parents = MenuItem::find()->where(['parent_id' => null])->andFilterWhere(['<>', 'id', $model->id])->orderBy('label')->all();
        return $this->render($model->isNewRecord ? 'create' : 'update', ['model' => $model, 'parents' => $parents]);
    }

    private function findModel($id)
    {
        $model = MenuItem::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        return $model;
    }
}
