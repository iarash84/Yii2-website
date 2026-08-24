<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Opportunity */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Opportunities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="opportunity-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th>{label}</th><td style="text-align: right">{value}</td></tr>',
        'attributes' => [
            'id',
            'name',
            'phoneNumber',
            'resume',
            'email:email',
            'createDateTime',
        ],
    ]) ?>

</div>
