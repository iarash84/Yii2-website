<?php
/**
 * Created by PhpStorm.
 * User: arc
 * Date: 5/17/2016
 * Time: 11:16 AM
 */
use frontend\widgets\RichTextEditor;
use frontend\widgets\Icon;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Home Update');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-page">
    <header class="settings-hero">
        <span class="settings-hero-icon"><?= Icon::show('edit', ['width' => 26, 'height' => 26]) ?></span>
        <div>
            <p class="text-overline"><?= Yii::t('app', 'Homepage content') ?></p>
            <h1><?= Html::encode($this->title) ?></h1>
            <p class="text-muted"><?= Yii::t('app', 'Manage the content displayed below the homepage hero section.') ?></p>
        </div>
    </header>

    <section class="settings-card card">
    <?php $form = ActiveForm::begin(); ?>

    <div class="settings-section-heading">
        <h2><?= Yii::t('app', 'Default language content') ?></h2>
        <p class="text-muted"><?= Yii::t('app', 'Edit the primary content first, then complete the available translations.') ?></p>
    </div>

    <?= $form->field($model, 'pageContent')->widget(RichTextEditor::class, ['rows' => 8]) ?>

    <?= $this->render('@app/modules/admin/views/_translation_fields', ['model' => $settingModel]) ?>

    <div class="form-actions">
        <?= Html::submitButton(Icon::show('edit') . Yii::t('app', 'Update'), ['class' =>'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </section>
</div>
