<?php

use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OpportunitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Job opportunity');
$this->params['breadcrumbs'][] = $this->title;


$setting =  new \frontend\models\Setting();
?>
<div class="opportunity-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= Tabs::widget([
        'navType' => 'nav-tabs nav-justified',
        'items' => [
            [
                'label' => Yii::t('app' ,'Request List'),
                'content' =>  '<br />'.$this->render('_requestList', ['dataProvider' => $dataProvider]),
                'active' => true
            ],
            [
                'label' => Yii::t('app' ,'View'),
                'content' =>  '<br />'.$setting->opportunity,
            ],
            [
                'label' => Yii::t('app' ,'Update'),
                'content' =>  '<br />'.$this->render('_update', ['model' => $model]),
            ]
        ],
    ]);
    ?>
</div>
