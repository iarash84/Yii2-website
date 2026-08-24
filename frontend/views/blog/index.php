<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use frontend\widgets\Icon;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\BlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Blog');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-index">
    <div class="page-header page-header-actions">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->can('manageContent')): ?>
            <?= Html::a(Icon::show('plus') . Yii::t('app', 'Create Post'), ['/admin/blog/create'], ['class' => 'btn']) ?>
        <?php endif; ?>
    </div>
    <!-- Page Layout here -->
    <div class="row">

        <div class="col s3">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><?= Html::a(Yii::t('app', 'Categories'), ['/blog/index']) ?></h2>
                </div>
                <ul class="list-group">
                    <?php
                    foreach($categoryModels as $category){
                        echo'<li class="list-group-item">'.Html::a(Html::encode($category->getLocalized('title')),['/blog/category', 'id'=>$category->id]).'</li>';
                    }
                    ?>
                </ul>
            </div>
            <?php if ($tagModels): ?>
                <div class="panel blog-tags-filter">
                    <div class="panel-heading"><h2><?= Yii::t('app', 'Hashtags') ?></h2></div>
                    <div class="tag-cloud">
                        <?php foreach ($tagModels as $tag): ?>
                            <?= Html::a('#' . Html::encode($tag->name), ['/blog/index', 'BlogSearch[tag]' => $tag->slug], [
                                'class' => 'tag-chip' . ($searchModel->tag === $tag->slug ? ' is-active' : ''),
                            ]) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col s9">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'options' => [
                    'tag' => 'div',
                    'class' => 'list-wrapper',
                    'id' => 'list-wrapper',
                ],
                'itemView' => function ($model, $key, $index, $widget) {
                    return $this->render('_post',['model' => $model]);
                },
            ]);
            ?>
        </div>
    </div>
</div>
