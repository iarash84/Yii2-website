<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Opportunity */

$this->title = Yii::t('app', 'Create Opportunity');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Opportunities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="opportunity-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
