<?php

use frontend\widgets\Icon;
use frontend\widgets\AdminActionColumn;
use frontend\widgets\AdminButton;
use frontend\widgets\StatusBadge;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Menu management');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">
    <div class="page-header page-header-actions">
        <div><p class="text-overline"><?= Yii::t('app', 'Navigation') ?></p><h1><?= Html::encode($this->title) ?></h1></div>
        <?= AdminButton::link(Icon::show('plus', ['width' => 18, 'height' => 18]) . Yii::t('app', 'Create menu item'), ['create'], 'primary') ?>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'label',
            'url',
            ['attribute' => 'location', 'value' => static function ($model) { return Yii::t('app', $model->location === 'main' ? 'Main menu' : 'Footer menu'); }],
            'sort_order',
            ['attribute' => 'status', 'format' => 'raw', 'value' => static fn ($model) => StatusBadge::boolean($model->status)],
            ['class' => AdminActionColumn::class, 'template' => '{update} {delete}'],
        ],
    ]) ?>
</div>
