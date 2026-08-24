<?php

use dosamigos\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin();
?>
<div class="card page-editor">
    <?= $form->errorSummary($model) ?>
    <div class="form-grid">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'slug')->textInput(['maxlength' => true, 'dir' => 'ltr', 'placeholder' => 'about-company']) ?>
        <?= $form->field($model, 'status')->dropDownList($model::statusOptions()) ?>
        <?= $form->field($model, 'featured_media_id')->dropDownList(ArrayHelper::map($media, 'id', 'original_name'), ['prompt' => Yii::t('app', 'No featured media')]) ?>
        <?= $form->field($model, 'publish_at')->input('datetime-local', ['value' => $model->publish_at ? date('Y-m-d\TH:i', $model->publish_at) : '']) ?>
        <?= $form->field($model, 'unpublish_at')->input('datetime-local', ['value' => $model->unpublish_at ? date('Y-m-d\TH:i', $model->unpublish_at) : '']) ?>
    </div>
    <?= $form->field($model, 'summary')->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'content')->widget(CKEditor::class, ['preset' => 'full', 'options' => ['rows' => 12]]) ?>

    <fieldset><legend><?= Yii::t('app', 'Search engine optimization') ?></legend>
        <?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'seo_description')->textarea(['rows' => 3, 'maxlength' => 320]) ?>
        <?= $form->field($model, 'seo_keywords')->textInput(['maxlength' => true]) ?>
        <div class="form-grid">
            <?= $form->field($model, 'canonical_url')->textInput(['dir' => 'ltr']) ?>
            <?= $form->field($model, 'robots')->dropDownList(['index,follow' => 'index,follow', 'noindex,follow' => 'noindex,follow', 'noindex,nofollow' => 'noindex,nofollow']) ?>
        </div>
    </fieldset>
    <?= $this->render('@app/modules/admin/views/_translation_fields', ['model' => $model]) ?>
    <div class="form-actions"><?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?> <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-secondary']) ?></div>
</div>
<?php ActiveForm::end(); ?>
