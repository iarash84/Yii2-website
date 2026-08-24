<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\components\TextCaptcha;

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

            <?= $form->field($model, 'resume')->fileInput([
                'accept' => 'application/pdf',
                'aria-describedby' => 'resume-help',
            ])->hint(Yii::t('app', 'Upload a PDF file within the allowed size.'), ['id' => 'resume-help']) ?>


            <div class="captcha-panel">
                <p id="captcha-question" class="captcha-question"><?= Html::encode(TextCaptcha::question()) ?></p>
                <?= $form->field($model, 'verifyCode')->textInput([
                    'inputmode' => 'numeric',
                    'autocomplete' => 'off',
                    'aria-describedby' => 'captcha-question',
                ]) ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
