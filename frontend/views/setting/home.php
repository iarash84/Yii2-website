<?php
/**
 * Created by PhpStorm.
 * User: arc
 * Date: 5/17/2016
 * Time: 11:16 AM
 */
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Home Update');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-social">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pageContent')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'class' => 'form-control',
        'preset' => 'full',
        'clientOptions' => [ 'filebrowserUploadUrl' => Url::to(['blog/upload']) , Yii::$app->language],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>