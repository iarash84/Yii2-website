<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('app','Job opportunity');;
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-contact">
    <h3><?= Html::encode($this->title) ?></h3>
    <div class="row">
        <?php
            $setting = new \frontend\models\Setting();
            echo $setting->opportunity;
        ?>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <?php $form = ActiveForm::begin(['id' => 'contact-form', 'options'=>['enctype'=>'multipart/form-data']]); ?>

            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'phoneNumber') ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'resume')->widget(FileInput::classname(), [
                'pluginOptions' => [
                    'showUpload' => false,
                    'showCaption' => false,
                    'previewFileType' => 'any',
                    'showRemove' => true,
                    'browseClass' => 'btn btn-info',
                    'removeClass' => 'btn btn-danger',
                    'removeIcon' => '<i class="glyphicon glyphicon-trash"></i> '
                ]
            ]);
            ?>


            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
