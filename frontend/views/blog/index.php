<?php

use yii\helpers\Html;
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
            <?= Html::a(Icon::show('plus') . Yii::t('app', 'Create Post'), ['/admin/blog/create'], ['class' => 'd-btn d-btn-primary']) ?>
        <?php endif; ?>
    </div>
    <div class="blog-layout">
        <aside class="blog-sidebar" aria-label="<?= Yii::t('app', 'Categories') ?>">
            <section class="blog-filter">
                <header class="blog-filter-heading">
                    <h2><?= Html::a(Yii::t('app', 'Categories'), ['/blog/index']) ?></h2>
                </header>
                <ul class="blog-filter-list">
                    <?php
                    foreach($categoryModels as $category){
                        echo '<li>' . Html::a(Html::encode($category->getLocalized('title')), ['/blog/category', 'id' => $category->id]) . '</li>';
                    }
                    ?>
                </ul>
            </section>
            <?php if ($tagModels): ?>
                <section class="blog-filter blog-tags-filter">
                    <header class="blog-filter-heading"><h2><?= Yii::t('app', 'Hashtags') ?></h2></header>
                    <div class="tag-cloud">
                        <?php foreach ($tagModels as $tag): ?>
                            <?= Html::a('#' . Html::encode($tag->name), ['/blog/index', 'BlogSearch[tag]' => $tag->slug], [
                                'class' => 'tag-chip' . ($searchModel->tag === $tag->slug ? ' is-active' : ''),
                            ]) ?>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </aside>
        <section class="blog-feed" aria-label="<?= Html::encode($this->title) ?>">
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
        </section>
    </div>
</div>
