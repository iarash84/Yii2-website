<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Category */

$this->title = Yii::t('app', 'Update') . ' : ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="category-update">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
