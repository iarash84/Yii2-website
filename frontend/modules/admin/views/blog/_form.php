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
        'clientOptions' => [ 'filebrowserUploadUrl' => Url::to(['/admin/blog/upload']) , 'language' => Yii::$app->language],
    ]) ?>

    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'class' => 'form-control',
        'preset' => 'full',
        'clientOptions' => [ 'filebrowserUploadUrl' => Url::to(['/admin/blog/upload']) , Yii::$app->language],
    ]) ?>

    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hashtags')->textInput([
        'maxlength' => true,
        'placeholder' => Yii::t('app', '#technology, #design'),
    ])->hint(Yii::t('app', 'Separate hashtags with commas or spaces.')) ?>

    <?= $this->render('@app/modules/admin/views/_translation_fields', ['model' => $model]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Send') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
