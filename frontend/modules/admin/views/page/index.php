<?php
use frontend\models\Page;
use frontend\widgets\Icon;
use frontend\widgets\AdminActionColumn;
use frontend\widgets\AdminButton;
use frontend\widgets\StatusBadge;
use yii\grid\GridView;
use yii\helpers\Html;
$this->title = Yii::t('app', 'Dynamic pages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <div class="page-header page-header-actions"><div><p class="text-overline"><?= Yii::t('app', 'Content') ?></p><h1><?= Html::encode($this->title) ?></h1></div><?= AdminButton::link(Icon::show('plus', ['width' => 18, 'height' => 18]) . Yii::t('app', 'Create page'), ['create'], 'primary') ?></div>
    <?= GridView::widget(['dataProvider' => $dataProvider, 'columns' => [
        'title', 'slug', ['attribute' => 'status', 'format' => 'raw', 'value' => static fn ($model) => StatusBadge::publication($model->status, Page::statusOptions()[$model->status] ?? $model->status)],
        ['attribute' => 'publish_at', 'format' => 'datetime'], ['attribute' => 'updated_at', 'format' => 'datetime'],
        ['class' => AdminActionColumn::class, 'template' => '{update} {delete}'],
    ]]) ?>
</div>
