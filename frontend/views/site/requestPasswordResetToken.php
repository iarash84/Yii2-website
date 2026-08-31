<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Request password reset');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset public-form-card auth-card">
    <header class="public-form-header"><h1><?= Html::encode($this->title) ?></h1>
    <p><?= Yii::t('app', 'Please fill out your email. A link to reset password will be sent there.') ?></p></header>
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'd-btn d-btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
</div>
