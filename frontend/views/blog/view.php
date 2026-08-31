<?php

use yii\helpers\Html;
use frontend\widgets\Icon;

/* @var $this yii\web\View */
/* @var $model frontend\models\Blog */

$this->title = $model->getLocalized('title');
$this->registerMetaTag(['name' => 'description', 'content' => strip_tags($model->getLocalized('description'))]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->getLocalized('keywords')]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Blog'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-view">
    <?php
    if (!Yii::$app->user->isGuest && Yii::$app->user->can('manageContent')) { ?>
        <div class="page-actions">
            <?= Html::a(Icon::show('edit') . Yii::t('app', 'Update'), ['/admin/blog/update', 'id' => $model->id], ['class' => 'd-btn d-btn-outline']) ?>
            <?= Html::a(Icon::show('trash') . Yii::t('app', 'Delete'), ['/admin/blog/delete', 'id' => $model->id], [
                'class' => 'd-btn d-btn-error d-btn-soft',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    <?php } ?>

    <article class="article-page">
        <header class="article-header"><p class="text-overline"><?= Yii::t('app', 'Blog') ?></p><h1><?= Html::encode($this->title) ?></h1>

        <div class="article-meta">
            <span>
                <?= Icon::show('users') ?> <?= Html::encode($model->user->username) ?>
            </span>
            <span>
                <time datetime="<?= Html::encode($model->created_at) ?>"><?= Yii::$app->formatter->asDatetime($model->created_at) ?></time>
            </span>
        </div></header>
        <div class="prose rich-content"><?= \yii\helpers\HtmlPurifier::process($model->getLocalized('content')) ?></div>
        <?php if ($model->tags): ?><div class="tag-cloud" aria-label="<?= Yii::t('app', 'Hashtags') ?>"><?php foreach ($model->tags as $tag): ?><?= Html::a('#' . Html::encode($tag->name), ['/blog/index', 'BlogSearch[tag]' => $tag->slug], ['class' => 'tag-chip']) ?><?php endforeach; ?></div><?php endif; ?>
    </article>

</div>
