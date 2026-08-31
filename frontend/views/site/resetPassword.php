<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Reset password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password public-form-card auth-card">
    <header class="public-form-header"><h1><?= Html::encode($this->title) ?></h1>
    <p><?= Yii::t('app', 'Please choose your new password:') ?></p></header>
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'd-btn d-btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
</div>
