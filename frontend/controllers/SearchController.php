<?php

namespace frontend\controllers;

use frontend\models\Blog;
use frontend\models\ContentTranslation;
use frontend\models\Page;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

class SearchController extends Controller
{
    public function actionIndex($q = '')
    {
        $q = trim((string) $q);
        $results = [];
        if (mb_strlen($q) >= 2 && mb_strlen($q) <= 100) {
            $language = Yii::$app->languageManager->activeLanguage;
            $translated = ContentTranslation::find()->select('entity_id')->where(['language' => $language])->andWhere(['like', 'value', $q]);
            $pageIds = (clone $translated)->andWhere(['entity_type' => 'Page'])->column();
            $blogIds = (clone $translated)->andWhere(['entity_type' => 'Blog'])->column();
            foreach (Page::published()->andWhere(['or', ['like', 'title', $q], ['like', 'summary', $q], ['like', 'content', $q], ['id' => $pageIds]])->limit(50)->all() as $page) {
                $results[] = ['type' => 'page', 'title' => $page->getLocalized('title'), 'summary' => $page->getLocalized('summary'), 'url' => ['/page/view', 'slug' => $page->getLocalized('slug')]];
            }
            foreach (Blog::find()->andWhere(['or', ['like', 'title', $q], ['like', 'description', $q], ['like', 'content', $q], ['id' => $blogIds]])->limit(50)->all() as $post) {
                $results[] = ['type' => 'blog', 'title' => $post->getLocalized('title'), 'summary' => $post->getLocalized('description'), 'url' => ['/blog/view', 'id' => $post->id]];
            }
        }
        return $this->render('index', ['query' => $q, 'dataProvider' => new ArrayDataProvider(['allModels' => $results, 'pagination' => ['pageSize' => 20]])]);
    }
}
