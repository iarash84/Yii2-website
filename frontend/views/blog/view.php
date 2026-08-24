<?php

use yii\helpers\Html;
use frontend\widgets\Icon;

/* @var $this yii\web\View */
/* @var $model frontend\models\Blog */

$this->title = $model->getLocalized('title');
$this->registerMetaTag(['name' => 'description', 'content' => strip_tags($model->getLocalized('description'))]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->getLocalized('keyWord')]);
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
        <h5> <?= Html::a(Html::encode($this->title) ,['blog/view','id' => $model->id, 'subject' => str_replace(' ','_',trim($this->title))]) ?></h5>

        <div class="row">
            <div class="group1 col-sm-6 col-md-6">
                <?= Icon::show('users') ?> <?= Html::encode($model->user->username) ?>

            </div>
            <div class="group2 col-sm-6 col-md-6">
                <time datetime="<?= Html::encode($model->createDatetime) ?>"><?= Html::encode($model->createDatetime) ?></time>
            </div>
        </div>
        <hr>
        <?= \yii\helpers\HtmlPurifier::process($model->getLocalized('content')) ?>
        <hr>
    </article>

</div>
