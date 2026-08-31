<?php

use frontend\models\Setting;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->title = Yii::t('app', 'Job opportunity');
$this->params['breadcrumbs'][] = $this->title;
$setting = new Setting();
?>
<div class="page-header"><h1><?= Html::encode($this->title) ?></h1></div>
<section class="card">
    <h2><?= Yii::t('app', 'Request List') ?></h2>
    <?= $this->render('_requestList', ['dataProvider' => $dataProvider]) ?>
</section>
<div class="admin-tabs section" data-admin-tabs>
    <div class="d-tabs d-tabs-box" role="tablist"><button type="button" class="d-tab d-tab-active" role="tab" aria-selected="true" data-tab-target="opportunity-preview"><?= Yii::t('app', 'View') ?></button><button type="button" class="d-tab" role="tab" aria-selected="false" data-tab-target="opportunity-edit"><?= Yii::t('app', 'Update') ?></button></div>
    <section id="opportunity-preview" class="card admin-tab-panel" role="tabpanel" data-tab-panel>
        <h2><?= Yii::t('app', 'View') ?></h2>
        <?= HtmlPurifier::process($setting->opportunity) ?>
    </section>
    <section id="opportunity-edit" class="card admin-tab-panel" role="tabpanel" data-tab-panel hidden>
        <h2><?= Yii::t('app', 'Update') ?></h2>
        <?= $this->render('_update', ['model' => $model]) ?>
    </section>
</div>
