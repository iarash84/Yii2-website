<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('app','About');
$this->params['breadcrumbs'][] = $this->title;
?>
<article class="site-about longform-page">
    <header class="page-header"><p class="text-overline"><?= Yii::t('app', 'About') ?></p><h1><?= Html::encode($this->title) ?></h1></header>
    <div class="prose rich-content" itemprop="articleBody">
    <?= \yii\helpers\HtmlPurifier::process($model->getLocalizedContent()) ?>
    </div>
</article>
