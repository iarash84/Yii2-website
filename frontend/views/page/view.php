<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$title = $model->getLocalized('seo_title') ?: $model->getLocalized('title');
$description = $model->getLocalized('seo_description') ?: $model->getLocalized('summary');
$this->title = $title;
$this->params['breadcrumbs'][] = $model->getLocalized('title');
if ($model->canonical_url) {
    $this->params['canonicalUrl'] = $model->canonical_url;
}
if ($description) {
    $this->registerMetaTag(['name' => 'description', 'content' => $description], 'description');
    $this->registerMetaTag(['property' => 'og:description', 'content' => $description], 'og-description');
}
if ($model->getLocalized('seo_keywords')) {
    $this->registerMetaTag(['name' => 'keywords', 'content' => $model->getLocalized('seo_keywords')], 'keywords');
}
$this->registerMetaTag(['name' => 'robots', 'content' => $model->robots], 'robots');
$this->registerMetaTag(['property' => 'og:title', 'content' => $title], 'og-title');
$this->registerMetaTag(['property' => 'og:type', 'content' => 'article'], 'og-type');
?>
<article class="dynamic-page">
    <header class="page-header">
        <p class="text-overline"><?= Yii::t('app', 'Page') ?></p>
        <h1><?= Html::encode($model->getLocalized('title')) ?></h1>
        <?php if ($model->getLocalized('summary')): ?><p class="page-lead"><?= Html::encode($model->getLocalized('summary')) ?></p><?php endif; ?>
    </header>
    <?php if ($model->featuredMedia && $model->featuredMedia->getIsImage()): ?>
        <?= Html::img($model->featuredMedia->getUrl(), ['class' => 'page-featured-image', 'alt' => Html::encode($model->featuredMedia->alt_text ?: $model->getLocalized('title'))]) ?>
    <?php endif; ?>
    <div class="card prose"><?= HtmlPurifier::process($model->getLocalized('content')) ?></div>
</article>
