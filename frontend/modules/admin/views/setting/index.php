<?php
/**
 * Created by PhpStorm.
 * User: arc
 * Date: 5/17/2016
 * Time: 11:16 AM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Setting');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'companyName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'postalCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phoneNumber')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'faxNumber')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'workingHours')->textInput(['maxlength' => true]) ?>

    <?php foreach (Yii::$app->languageManager->languages as $code => $language): ?>
        <?php if ($code === Yii::$app->languageManager->defaultLanguage) {
            continue;
        } ?>
        <fieldset dir="<?= ($language['direction'] ?? 'ltr') === 'rtl' ? 'rtl' : 'ltr' ?>">
            <legend><?= Html::encode($language['label']) ?></legend>
            <?php foreach (['CompanyName', 'Address', 'WorkingHours'] as $type): ?>
                <?php $settingModel = $translatedSettings[$type]; ?>
                <div class="form-group">
                    <?= Html::label(Yii::t('app', preg_replace('/(?<!^)[A-Z]/', ' $0', $type))) ?>
                    <?= Html::textInput(
                        "settingTranslations[{$type}][{$code}][content]",
                        $settingModel ? $settingModel->getTranslationValue('content', $code) : null,
                        ['class' => 'form-control']
                    ) ?>
                </div>
            <?php endforeach; ?>
        </fieldset>
    <?php endforeach; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
