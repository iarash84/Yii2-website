<?php

namespace frontend\modules\admin\controllers;


use frontend\models\Category;
use Yii;
use frontend\models\Blog;
use frontend\models\BlogSearch;
use frontend\models\BlogTag;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BlogController implements the CRUD actions for Blog model.
 */
class BlogController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [ 'update', 'delete', 'create'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Blog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlogSearch();
        $categoryModels = Category::find()->all();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@app/views/blog/index', [
            'searchModel' => $searchModel,
            'categoryModels'=>$categoryModels,
            'dataProvider' => $dataProvider,
            'tagModels' => BlogTag::find()->orderBy(['name' => SORT_ASC])->all(),
        ]);
    }

    /**
     * Displays a single Blog model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('@app/views/blog/view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Lists all Post filterd by categoryId.
     * @return mixed
     */
    public function actionCategory($id){
        $searchModel = new BlogSearch();
        $categoryModels = Category::find()->all();

        $query = Blog::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->andFilterWhere([
            'category_id' => $id,
        ]);

        return $this->render('@app/views/blog/index', [
            'searchModel' => $searchModel,
            'categoryModels'=>$categoryModels,
            'dataProvider' => $dataProvider,
            'tagModels' => BlogTag::find()->orderBy(['name' => SORT_ASC])->all(),
        ]);
    }


    /**
     * Creates a new Blog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Blog();

        if ($model->load(Yii::$app->request->post())) {

            $model->user_id = Yii::$app->user->identity->getId();
            if ($model->save() && $model->syncTags($model->hashtags)
                && $model->saveTranslations(Yii::$app->request->post('translations', []))) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $categoriesModel = Category::find()->all();
            $categories = array();
            foreach ($categoriesModel as $category) {
                $categories[$category->id] = $category->title;
            }

            return $this->render('create', [
                'model' => $model,
                'categories' => $categories
            ]);
        }
    }

    /**
     * Updates an existing Blog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save() && $model->syncTags($model->hashtags)
            && $model->saveTranslations(Yii::$app->request->post('translations', []))) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {

            $categoriesModel = Category::find()->all();
            $categories = array();
            foreach ($categoriesModel as $category) {
                $categories[$category->id] = $category->title;
            }

            return $this->render('update', [
                'model' => $model,
                'categories' => $categories
            ]);
        }
    }

    /**
     * Deletes an existing Blog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Blog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Blog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
