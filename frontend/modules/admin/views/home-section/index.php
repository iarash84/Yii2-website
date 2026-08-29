<?php
use frontend\models\HomeSection;
use yii\helpers\Html;
use yii\helpers\Url;
$this->title=Yii::t('app','Homepage sections');
?>
<div class="page-header page-header-actions"><div><p class="text-overline"><?= Yii::t('app','Homepage builder') ?></p><h1><?= Html::encode($this->title) ?></h1><p><?= Yii::t('app','Change section order, visibility and content without editing code.') ?></p></div><?= Html::a(Yii::t('app','Add section'),['create'],['class'=>'btn']) ?></div>
<div class="home-section-sorter" data-home-section-sorter data-save-url="<?= Url::to(['organize']) ?>" data-csrf-param="<?= Html::encode(Yii::$app->request->csrfParam) ?>" data-csrf-token="<?= Html::encode(Yii::$app->request->csrfToken) ?>">
<?php foreach ($dataProvider->getModels() as $model): ?>
    <article class="card home-section-row" draggable="true" data-section-id="<?= (int)$model->id ?>">
        <span class="drag-handle" title="<?= Yii::t('app','Drag to reorder') ?>" aria-hidden="true">⋮⋮</span>
        <div class="home-section-row-content"><strong><?= Html::encode($model->title) ?></strong><small><?= Html::encode(HomeSection::typeOptions()[$model->type] ?? $model->type) ?></small></div>
        <label class="toggle-field"><input type="checkbox" data-section-enabled <?= $model->status ? 'checked' : '' ?>> <span><?= Yii::t('app','Published') ?></span></label>
        <div class="action-row"><?= Html::a(Yii::t('app','Update'),['update','id'=>$model->id],['class'=>'btn btn-secondary']) ?><?= Html::a(Yii::t('app','Delete'),['delete','id'=>$model->id],['class'=>'btn btn-danger','data-method'=>'post','data-confirm'=>Yii::t('app','Are you sure you want to delete this item?')]) ?></div>
    </article>
<?php endforeach; ?>
</div>
<p class="save-state text-muted" data-home-section-save-state aria-live="polite"></p>
