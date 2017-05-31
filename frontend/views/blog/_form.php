<?php

use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Blog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?=  $form->field($model, 'category_id')->dropDownList($categories , ['prompt'=>'']) ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 3],

        'class' => 'form-control',
        'preset' => 'full',
        'clientOptions' => [ 'filebrowserUploadUrl' => Url::to(['blog/upload']) , 'language' => Yii::$app->language],
    ]) ?>

    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'class' => 'form-control',
        'preset' => 'full',
        'clientOptions' => [ 'filebrowserUploadUrl' => Url::to(['blog/upload']) , Yii::$app->language],
    ]) ?>

    <?= $form->field($model, 'keyWord')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Send') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
