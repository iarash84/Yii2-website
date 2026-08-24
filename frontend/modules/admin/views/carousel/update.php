<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Carousel */

$this->title = Yii::t('app', 'Update Carousel') . ' : ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Carousels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="carousel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
