<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'enableClientValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>
<?php //if($model->image){ ?>
    <!--    <img src="--><?php //= $model->image ?><!--" style="width: 848px">-->
<?php //} ?>
<?= $form->field($model, 'image')->fileInput() ?>
<?= $form->field($model, 'link',
    ['inputOptions'=>[
        'class'=>'form-control',
        'style'=>'margin-top:20px;text-align: left',
    ]   ]) ?>

<?= $form->field($model, 'title')->textarea() ?>
<?= $form->field($model, 'text')->textarea() ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>