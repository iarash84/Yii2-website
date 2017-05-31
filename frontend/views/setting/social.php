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
<div class="setting-social">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'facebook')->textInput(['maxlength' => true, 'style' => 'text-align : left']) ?>

    <?= $form->field($model, 'googlePlus')->textInput(['maxlength' => true, 'style' => 'text-align : left']) ?>

    <?= $form->field($model, 'instagram')->textInput(['maxlength' => true, 'style' => 'text-align : left']) ?>

    <?= $form->field($model, 'twitter')->textInput(['maxlength' => true, 'style' => 'text-align : left']) ?>

    <?= $form->field($model, 'linkedin')->textInput(['maxlength' => true, 'style' => 'text-align : left']) ?>

    <?= $form->field($model, 'aparat')->textInput(['maxlength' => true, 'style' => 'text-align : left']) ?>

    <?= $form->field($model, 'youtube')->textInput(['maxlength' => true, 'style' => 'text-align : left']) ?>

    <?= $form->field($model, 'telegram')->textInput(['maxlength' => true, 'style' => 'text-align : left']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>