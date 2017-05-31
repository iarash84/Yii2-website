<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'System');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="setting-system">

    <div class="form-group" style="padding: 10px">
        <?= Html::a('<i class="glyphicon glyphicon-flash"></i>'. Yii::t('app', 'Flush cache') , ['setting/flush'], ['class' => 'btn btn-info', 'style' => 'margin : 10px']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-trash"></i>'. Yii::t('app', 'Clear assets') , ['setting/clear'], ['class' => 'btn btn-primary' , 'style' => 'margin : 10px']) ?>

    </div>

</div>