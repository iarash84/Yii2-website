<?php
use frontend\widgets\Icon;
use frontend\widgets\AdminActionColumn;
use frontend\widgets\AdminButton;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = Yii::t('app', 'Media library'); $this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-index">
    <div class="page-header"><p class="text-overline"><?= Yii::t('app', 'Files and images') ?></p><h1><?= Html::encode($this->title) ?></h1></div>
    <div class="card media-upload">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <?= $form->errorSummary($model) ?>
        <div class="form-grid"><div class="form-group"><label for="media-file"><?= Yii::t('app', 'Select file') ?></label><?= Html::fileInput('mediaFile', null, ['id' => 'media-file', 'accept' => 'image/png,image/jpeg,image/gif,image/webp,application/pdf', 'required' => true]) ?></div><div class="form-group"><label for="media-alt"><?= Yii::t('app', 'Alternative text') ?></label><?= Html::textInput('altText', null, ['id' => 'media-alt', 'class' => 'form-control']) ?></div></div>
        <?= AdminButton::submit(Icon::show('upload', ['width' => 18, 'height' => 18]) . Yii::t('app', 'Upload')) ?>
        <?php ActiveForm::end(); ?>
    </div>
    <?= GridView::widget(['dataProvider' => $dataProvider, 'columns' => [
        ['label' => Yii::t('app', 'Preview'), 'format' => 'raw', 'value' => static function ($item) {
            if (!$item->getIsImage()) {
                return Icon::show('posts');
            }
            $alt = $item->alt_text ?: $item->original_name;
            return Html::button(Html::img($item->getUrl(), ['class' => 'carousel-thumbnail-image', 'alt' => Html::encode($alt)]), [
                'type' => 'button',
                'class' => 'carousel-thumbnail media-preview-button',
                'data-image-preview' => $item->getUrl(),
                'data-image-alt' => $alt,
                'title' => Yii::t('app', 'View full-size image'),
                'aria-label' => Yii::t('app', 'View full-size image'),
            ]);
        }],
        'original_name', 'mime_type', ['attribute' => 'size', 'value' => static fn ($item) => Yii::$app->formatter->asShortSize($item->size)],
        ['label' => Yii::t('app', 'Public URL'), 'format' => 'raw', 'value' => static fn ($item) => Html::textInput('', $item->getUrl(), ['class' => 'form-control ltr', 'readonly' => true])],
        ['class' => AdminActionColumn::class, 'template' => '{delete}'],
    ]]) ?>
</div>
