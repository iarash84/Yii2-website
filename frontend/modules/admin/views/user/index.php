<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'User Management');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php  echo $this->render('signup', ['model' => $model]); ?>

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
                'attribute' => 'username'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'attribute' => 'email'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'value' => function ($data) {
                    return $data->created_at;//Yii::$app->DateTimeConverter->gregorian_to_jalali($data->created_at);
                },
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'attribute' => 'created_at',
            ],
            [
                'class' => 'yii\grid\ActionColumn' ,
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'template' => '{update} {delete}'
            ]
        ],
    ]); ?>
    <br /><br />
</div>
