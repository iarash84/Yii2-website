<?php
use frontend\widgets\AdminButton;
use frontend\widgets\Icon;
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = Yii::t('app', 'FAQ management');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-admin">
    <div class="page-header page-header-actions">
        <div><p class="text-overline"><?= Yii::t('app', 'Help center') ?></p><h1><?= Html::encode($this->title) ?></h1></div>
        <?= AdminButton::link(Icon::show('plus', ['width' => 18, 'height' => 18]) . Yii::t('app', 'Create FAQ'), ['create'], 'primary') ?>
    </div>
    <div class="faq-sorter" data-faq-sorter data-save-url="<?= Url::to(['reorder']) ?>" data-csrf-param="<?= Html::encode(Yii::$app->request->csrfParam) ?>" data-csrf-token="<?= Html::encode(Yii::$app->request->csrfToken) ?>">
        <?php foreach ($dataProvider->models as $model): ?><article class="card faq-sort-row" draggable="true" data-faq-id="<?= (int) $model->id ?>"><span class="drag-handle" aria-hidden="true">⋮⋮</span><div><strong><?= Html::encode($model->getLocalized('question')) ?></strong><small><?= $model->status ? Yii::t('app', 'Published') : Yii::t('app', 'Disabled') ?></small></div><div class="action-row"><?= AdminButton::link(Icon::show('edit'), ['update', 'id' => $model->id], 'compact', ['aria-label' => Yii::t('app', 'Update')]) ?><?= AdminButton::link(Icon::show('trash'), ['delete', 'id' => $model->id], 'danger-soft', ['data-method' => 'post', 'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'aria-label' => Yii::t('app', 'Delete')]) ?></div></article><?php endforeach; ?>
    </div>
</div>
