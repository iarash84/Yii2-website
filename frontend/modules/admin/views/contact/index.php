<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Contact');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>


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
                'attribute' => 'phoneNumber'
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
                'attribute' => 'subject'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'attribute' => 'body'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'attribute' => 'createDateTime',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style'=>'text-align:center;'],
                'contentOptions' => ['style'=>'text-align:center;'],
                'template' => '{view}     {delete}'
            ],
        ],
    ]); ?>

    <br /><br />

</div>
