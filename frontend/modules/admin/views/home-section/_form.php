<?php
use frontend\models\HomeSection;
use yii\widgets\ActiveForm;
use frontend\widgets\RichTextEditor;
use frontend\widgets\AdminButton;
use frontend\widgets\Icon;
$form=ActiveForm::begin();
?>
<div class="card"><?= $form->errorSummary($model) ?><div class="form-grid"><?= $form->field($model,'type')->dropDownList(HomeSection::typeOptions()) ?><?= $form->field($model,'sort_order')->input('number') ?></div><?= $form->field($model,'title')->textInput(['maxlength'=>true]) ?><?= $form->field($model,'subtitle')->textarea(['rows'=>2]) ?><?= $form->field($model,'content')->widget(RichTextEditor::class, ['rows'=>10]) ?><p class="text-muted"><?= Yii::t('app','For dynamic sections, content is shown as the introductory text and items are loaded automatically.') ?></p><?= $form->field($model,'status')->checkbox() ?><?= $this->render('@app/modules/admin/views/_translation_fields',['model'=>$model]) ?><div class="form-actions"><?= AdminButton::submit(Icon::show('save', ['width' => 18, 'height' => 18]) . Yii::t('app','Save')) ?> <?= AdminButton::link(Yii::t('app','Cancel'),['index'],'ghost') ?></div></div><?php ActiveForm::end(); ?>
