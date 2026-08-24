<?php
use frontend\models\Page;
use frontend\widgets\Icon;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
$this->title = Yii::t('app', 'Dynamic pages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <div class="page-header page-header-actions"><div><p class="text-overline"><?= Yii::t('app', 'Content') ?></p><h1><?= Html::encode($this->title) ?></h1></div><?= Html::a(Icon::show('plus') . Yii::t('app', 'Create page'), ['create'], ['class' => 'btn']) ?></div>
    <?= GridView::widget(['dataProvider' => $dataProvider, 'columns' => [
        'title', 'slug', ['attribute' => 'status', 'value' => static fn ($model) => Page::statusOptions()[$model->status] ?? $model->status],
        ['attribute' => 'publish_at', 'format' => 'datetime'], ['attribute' => 'updated_at', 'format' => 'datetime'],
        ['class' => ActionColumn::class, 'template' => '{update} {delete}'],
    ]]) ?>
</div>
