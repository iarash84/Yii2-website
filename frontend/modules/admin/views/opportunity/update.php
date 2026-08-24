<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Opportunity */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Opportunity',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Opportunities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="opportunity-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
