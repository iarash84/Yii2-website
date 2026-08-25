<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->title = Yii::t('app', 'FAQS');
$this->params['breadcrumbs'][] = $this->title;
?>
<header class="faq-hero"><div><p class="text-overline"><?= Yii::t('app', 'Help center') ?></p><h1><?= Html::encode($this->title) ?></h1><p><?= Yii::t('app','Find clear answers to the most common questions.') ?></p></div><span class="faq-count"><?= count($models) ?><small><?= Yii::t('app','Answers') ?></small></span></header>
<div class="faq-list" data-faq-list>
    <?php if (!$models): ?>
        <div class="card empty-state"><?= Yii::t('app', 'No content is available yet.') ?></div>
    <?php endif; ?>
    <?php foreach ($models as $model): ?>
        <details class="card faq-item">
            <summary><span><?= Html::encode($model->getLocalized('question')) ?></span><span class="faq-toggle" aria-hidden="true">+</span></summary>
            <div class="faq-answer"><?= HtmlPurifier::process($model->getLocalized('answer')) ?></div>
        </details>
    <?php endforeach; ?>
</div>
