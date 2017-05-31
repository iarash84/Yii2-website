<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Sample */

$this->title = Yii::t('app', 'Create Sample');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sample Project'), 'url' => ['site/sample']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sample-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
