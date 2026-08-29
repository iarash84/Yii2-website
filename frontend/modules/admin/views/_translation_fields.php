<?php

use yii\helpers\Html;

/* @var $model frontend\models\Blog|frontend\models\Category|frontend\models\Setting */

$manager = Yii::$app->languageManager;
?>
<?php foreach ($manager->languages as $code => $language): ?>
    <?php if ($code === $manager->defaultLanguage) {
        continue;
    } ?>
    <fieldset class="translation-fields" dir="<?= ($language['direction'] ?? 'ltr') === 'rtl' ? 'rtl' : 'ltr' ?>">
        <legend><?= Html::encode($language['label']) ?></legend>
        <?php foreach ($model->translatedAttributes() as $attribute): ?>
            <div class="form-group">
                <?= Html::label($model->getAttributeLabel($attribute), "translation-{$code}-{$attribute}") ?>
                <?= in_array($attribute, ['content', 'description', 'answer'], true)
                    ? Html::textarea("translations[{$code}][{$attribute}]", $model->getTranslationValue($attribute, $code), [
                        'id' => "translation-{$code}-{$attribute}", 'class' => 'form-control rich-text-source', 'rows' => 6, 'data-rich-editor' => true,
                    ])
                    : Html::textInput("translations[{$code}][{$attribute}]", $model->getTranslationValue($attribute, $code), [
                        'id' => "translation-{$code}-{$attribute}", 'class' => 'form-control',
                    ]) ?>
            </div>
        <?php endforeach; ?>
    </fieldset>
<?php endforeach; ?>
