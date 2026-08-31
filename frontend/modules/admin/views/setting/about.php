<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->title = Yii::t('app', 'About');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header"><h1><?= Html::encode($this->title) ?></h1></div>
<div class="admin-tabs section" data-admin-tabs>
    <div class="d-tabs d-tabs-box" role="tablist"><button type="button" class="d-tab d-tab-active" role="tab" aria-selected="true" data-tab-target="about-preview"><?= Yii::t('app', 'View') ?></button><button type="button" class="d-tab" role="tab" aria-selected="false" data-tab-target="about-edit"><?= Yii::t('app', 'Update') ?></button></div>
    <section id="about-preview" class="card admin-tab-panel" role="tabpanel" data-tab-panel>
        <h2><?= Yii::t('app', 'View') ?></h2>
        <div class="prose"><?= HtmlPurifier::process($model->getLocalizedContent()) ?></div>
    </section>
    <section id="about-edit" class="card admin-tab-panel" role="tabpanel" data-tab-panel hidden>
        <h2><?= Yii::t('app', 'Update') ?></h2>
        <?= $this->render('_form', ['model' => $model]) ?>
    </section>
</div>
