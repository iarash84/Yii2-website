<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\components\TextCaptcha;

$this->title = Yii::t('app','Order app');;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-order public-form-card">
    <header class="public-form-header"><h1><?= Html::encode($this->title) ?></h1></header>

            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'phoneNumber') ?>

                <?= $form->field($model, 'company') ?>

                <?= $form->field($model, 'website') ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'description')->textArea(['rows' => 6]) ?>

                <div class="captcha-panel">
                    <p id="captcha-question" class="captcha-question"><?= Html::encode(TextCaptcha::question()) ?></p>
                    <?= $form->field($model, 'verifyCode')->textInput([
                        'inputmode' => 'numeric',
                        'autocomplete' => 'off',
                        'aria-describedby' => 'captcha-question',
                    ]) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'd-btn d-btn-primary', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
</div>
