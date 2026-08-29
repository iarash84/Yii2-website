<?php

use frontend\widgets\RichTextEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Blog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?=  $form->field($model, 'category_id')->dropDownList($categories , ['prompt'=>'']) ?>

    <?= $form->field($model, 'description')->widget(RichTextEditor::class, ['rows' => 4]) ?>

    <?= $form->field($model, 'content')->widget(RichTextEditor::class, ['rows' => 10]) ?>

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
