<?php
use frontend\widgets\AdminActionColumn;
use frontend\widgets\AdminButton;
use frontend\widgets\Icon;
use frontend\widgets\StatusBadge;
use yii\grid\GridView;
use yii\helpers\Html;
$this->title = Yii::t('app', 'FAQ management');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-admin">
    <div class="page-header page-header-actions">
        <div><p class="text-overline"><?= Yii::t('app', 'Help center') ?></p><h1><?= Html::encode($this->title) ?></h1></div>
        <?= AdminButton::link(Icon::show('plus', ['width' => 18, 'height' => 18]) . Yii::t('app', 'Create FAQ'), ['create'], 'primary') ?>
    </div>
    <?= GridView::widget(['dataProvider' => $dataProvider, 'columns' => [
        'question',
        'sort_order',
        ['attribute' => 'status', 'format' => 'raw', 'value' => static fn ($model) => StatusBadge::boolean($model->status)],
        ['class' => AdminActionColumn::class, 'template' => '{update} {delete}'],
    ]]) ?>
</div>
