<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\BlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Blog');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-index">
    <!-- Page Layout here -->
    <div class="row">

        <div class="col s3">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5><?= Html::a(Yii::t('app','Categories'),['/blogs']) ?></h5>
                </div>
                <ul class="list-group">
                    <?php
                    foreach($categoryModels as $category){
                        echo'<li class="list-group-item">'.Html::a($category->title,['/blog/category', 'id'=>$category->id]).'</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="col s9">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'options' => [
                    'tag' => 'div',
                    'class' => 'list-wrapper',
                    'id' => 'list-wrapper',
                ],
                'itemView' => function ($model, $key, $index, $widget) {
                    return $this->render('_post',['model' => $model]);
                },
            ]);
            ?>
        </div>
    </div>
</div>
