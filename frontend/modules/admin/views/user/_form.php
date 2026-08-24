<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="user-update">


    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'role')->dropDownList([
        'editor' => Yii::t('app', 'Editor'),
        'admin' => Yii::t('app', 'Admin'),
        'superAdmin' => Yii::t('app', 'Super Admin'),
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app','Update'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
