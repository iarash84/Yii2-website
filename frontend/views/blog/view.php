<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Blog */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Blog'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-view">
    <?php
    if(!Yii::$app->user->isGuest){ ?>
        <p>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php } ?>

    <article>
        <h5> <?= Html::a(Html::encode($model->title) ,['blog/view','id' => $model->id, 'subject' => str_replace(' ','_',trim($model->title))]) ?></h5>

        <div class="row">
            <div class="group1 col-sm-6 col-md-6">
                <span class="glyphicon glyphicon-folder-open" style="margin-left: 5px"></span><?= $model->user->username ?></a>

            </div>
            <div class="group2 col-sm-6 col-md-6">
                <span class="glyphicon glyphicon-time"></span> <?= $model->createDatetime ?>
            </div>
        </div>
        <hr>
        <?= $model->content ?>
        <hr>
    </article>

</div>
