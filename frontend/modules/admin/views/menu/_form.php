<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin();
?>
<div class="card menu-form">
    <?= $form->errorSummary($model) ?>
    <div class="form-grid">
        <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'url')->textInput(['maxlength' => true, 'placeholder' => '/contact']) ?>
        <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map($parents, 'id', 'label'), ['prompt' => Yii::t('app', 'No parent')]) ?>
        <?= $form->field($model, 'location')->dropDownList(['main' => Yii::t('app', 'Main menu'), 'footer' => Yii::t('app', 'Footer menu')]) ?>
        <?= $form->field($model, 'target')->dropDownList(['_self' => Yii::t('app', 'Same window'), '_blank' => Yii::t('app', 'New window')]) ?>
        <?= $form->field($model, 'sort_order')->input('number') ?>
    </div>
    <?= $form->field($model, 'status')->checkbox() ?>
    <?= $this->render('@app/modules/admin/views/_translation_fields', ['model' => $model]) ?>
    <div class="form-actions">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
