<?php
/**
 * Created by PhpStorm.
 * User: arc
 * Date: 5/17/2016
 * Time: 11:16 AM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Social Network');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-page">
    <header class="settings-hero"><div><p class="text-overline"><?= Yii::t('app', 'Online presence') ?></p><h1><?= Html::encode($this->title) ?></h1><p class="text-muted"><?= Yii::t('app', 'Add complete HTTPS links for the networks shown in the footer.') ?></p></div></header>
    <div class="card">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'facebook')->textInput(['maxlength' => true, 'dir' => 'ltr']) ?>

    <?= $form->field($model, 'instagram')->textInput(['maxlength' => true, 'dir' => 'ltr']) ?>

    <?= $form->field($model, 'twitter')->textInput(['maxlength' => true, 'dir' => 'ltr']) ?>

    <?= $form->field($model, 'linkedin')->textInput(['maxlength' => true, 'dir' => 'ltr']) ?>

    <?= $form->field($model, 'aparat')->textInput(['maxlength' => true, 'dir' => 'ltr']) ?>

    <?= $form->field($model, 'youtube')->textInput(['maxlength' => true, 'dir' => 'ltr']) ?>

    <?= $form->field($model, 'telegram')->textInput(['maxlength' => true, 'dir' => 'ltr']) ?>

    <div class="form-actions">
        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
