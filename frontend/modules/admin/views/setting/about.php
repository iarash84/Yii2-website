<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->title = Yii::t('app', 'About');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header"><h1><?= Html::encode($this->title) ?></h1></div>
<div class="card-grid admin-panels">
    <section class="card">
        <h2><?= Yii::t('app', 'View') ?></h2>
        <div class="prose"><?= HtmlPurifier::process($model->getLocalizedContent()) ?></div>
    </section>
    <section class="card">
        <h2><?= Yii::t('app', 'Update') ?></h2>
        <?= $this->render('_form', ['model' => $model]) ?>
    </section>
</div>
