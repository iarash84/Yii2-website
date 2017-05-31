<?php

/* @var $this yii\web\View */

use sjaakp\bandoneon\Bandoneon;
use yii\helpers\Html;



$this->title = Yii::t('app' ,'FAQS');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php Bandoneon::begin() ?>
    <?php foreach($models as $model) {  ?>
        <h4 class="form-control" ><?= $model->question ?></h4>
        <div class="box-body"><?= $model->respons ?></div>
    <?php } ?>
    <?php Bandoneon::end() ?>
</div>
