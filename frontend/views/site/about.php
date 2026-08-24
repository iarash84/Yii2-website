<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('app','About');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about" style="margin-bottom: 75px">
    <h3><?= Html::encode($this->title) ?></h3>

    <div itemprop="articleBody">
    <?= \yii\helpers\HtmlPurifier::process($model->getLocalizedContent()) ?>
    </div>
</div>
