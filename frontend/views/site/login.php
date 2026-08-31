<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model common\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app','Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login public-form-card auth-card">
    <header class="public-form-header"><h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Please fill out the following fields to login') ?></p></header>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'public-form'],
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'rememberMe')->checkbox([
        'template' => "<div>{input} {label}</div>\n<div>{error}</div>",
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'd-btn d-btn-primary', 'name' => 'login-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>


</div>
