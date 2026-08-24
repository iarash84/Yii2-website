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
<div class="card-grid admin-panels section">
    <section class="card">
        <h2><?= Yii::t('app', 'View') ?></h2>
        <?= HtmlPurifier::process($setting->opportunity) ?>
    </section>
    <section class="card">
        <h2><?= Yii::t('app', 'Update') ?></h2>
        <?= $this->render('_update', ['model' => $model]) ?>
    </section>
</div>
