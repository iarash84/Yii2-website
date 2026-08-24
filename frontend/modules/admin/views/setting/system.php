<?php

use yii\helpers\Html;
use frontend\widgets\Icon;

$this->title = Yii::t('app', 'System');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="settings-page">
    <header class="settings-hero">
        <span class="settings-hero-icon settings-hero-icon-mint"><?= Icon::show('settings', ['width' => 26, 'height' => 26]) ?></span>
        <div>
            <p class="text-overline"><?= Yii::t('app', 'System tools') ?></p>
            <h1><?= Html::encode($this->title) ?></h1>
            <p class="text-muted"><?= Yii::t('app', 'Safely maintain temporary files and generated resources.') ?></p>
        </div>
    </header>

    <div class="system-tool-grid">
        <article class="system-tool-card card">
            <span class="card-icon"><?= Icon::show('settings') ?></span>
            <h2><?= Yii::t('app', 'Application cache') ?></h2>
            <p class="text-muted"><?= Yii::t('app', 'Remove cached application data so fresh values are generated on the next request.') ?></p>
            <?= Html::a(Icon::show('settings') . Yii::t('app', 'Flush cache'), ['/admin/setting/flush'], [
                'class' => 'btn btn-secondary',
                'data-method' => 'post',
            ]) ?>
        </article>
        <article class="system-tool-card card">
            <span class="card-icon"><?= Icon::show('settings') ?></span><h2><?= Yii::t('app', 'Maintenance mode') ?></h2><p class="text-muted"><?= Yii::t('app', 'Temporarily close public pages while administrators retain access.') ?></p>
            <?= Html::beginForm(['/admin/setting/maintenance'], 'post') ?>
            <label><?= Html::checkbox('enabled', filter_var(\frontend\models\SystemSetting::getValue('maintenance_enabled', '0'), FILTER_VALIDATE_BOOLEAN)) ?> <?= Yii::t('app','Enable maintenance mode') ?></label>
            <?= Html::textarea('message', \frontend\models\SystemSetting::getValue('maintenance_message'), ['class'=>'form-control','rows'=>3,'placeholder'=>Yii::t('app','Maintenance message')]) ?>
            <div class="form-actions"><?= Html::submitButton(Yii::t('app','Save'), ['class'=>'btn']) ?></div><?= Html::endForm() ?>
        </article>

        <article class="system-tool-card system-tool-card-warning card">
            <span class="card-icon"><?= Icon::show('delete') ?></span>
            <h2><?= Yii::t('app', 'Generated assets') ?></h2>
            <p class="text-muted"><?= Yii::t('app', 'Delete published CSS and JavaScript copies. They will be rebuilt automatically.') ?></p>
            <?= Html::a(Icon::show('delete') . Yii::t('app', 'Clear assets'), ['/admin/setting/clear'], [
                'class' => 'btn btn-danger',
                'data-method' => 'post',
                'data-confirm' => Yii::t('app', 'Are you sure you want to clear generated assets?'),
            ]) ?>
        </article>
    </div>
</div>
