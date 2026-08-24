<?php

use yii\helpers\Html;
use frontend\widgets\Icon;

$this->title = Yii::t('app', 'System');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="setting-system">

    <div class="form-group" style="padding: 10px">
        <?= Html::a(Icon::show('settings') . Yii::t('app', 'Flush cache'), ['/admin/setting/flush'], ['class' => 'btn btn-secondary', 'data-method' => 'post']) ?>
        <?= Html::a(Icon::show('delete') . Yii::t('app', 'Clear assets'), ['/admin/setting/clear'], ['class' => 'btn btn-danger', 'data-method' => 'post']) ?>

    </div>

</div>
