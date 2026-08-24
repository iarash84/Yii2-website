<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->title = Yii::t('app', 'FAQS');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header"><h1><?= Html::encode($this->title) ?></h1></div>
<div class="faq-list">
    <?php if (!$models): ?>
        <div class="card empty-state"><?= Yii::t('app', 'No content is available yet.') ?></div>
    <?php endif; ?>
    <?php foreach ($models as $model): ?>
        <details class="card faq-item">
            <summary><?= Html::encode($model->question) ?></summary>
            <div class="faq-answer"><?= HtmlPurifier::process($model->respons) ?></div>
        </details>
    <?php endforeach; ?>
</div>
