<?php

namespace frontend\controllers;

use frontend\models\Blog;
use frontend\models\BlogSearch;
use frontend\models\Category;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BlogController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new BlogSearch();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'categoryModels' => Category::find()->all(),
            'dataProvider' => $searchModel->search(\Yii::$app->request->queryParams),
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCategory($id)
    {
        $searchModel = new BlogSearch();
        $query = Blog::find()->andWhere(['category_id' => $id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'categoryModels' => Category::find()->all(),
            'dataProvider' => new ActiveDataProvider(['query' => $query]),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
