<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\widgets\AdminActionColumn;
use frontend\widgets\Icon;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Order app');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'striped responsive-table' ],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'attribute' => 'name'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'attribute' => 'company'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'attribute' => 'phone_number'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'attribute' => 'website'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'attribute' => 'email'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'attribute' => 'created_at',
                'format' => 'datetime',
            ],
            [
                'class' => AdminActionColumn::class,
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'template' => '{detail} {delete}',
                'buttons' => ['detail' => static fn ($url, $model) => Html::button(Icon::show('eye'), ['class' => 'd-btn d-btn-sm d-btn-square d-btn-ghost', 'data-remote-dialog-url' => Url::to(['detail', 'id' => $model->id]), 'data-error-message' => Yii::t('app', 'Unable to load details.'), 'aria-label' => Yii::t('app', 'View')])],
            ],
        ],
    ]); ?>
    <br /><br />
</div>
