<?php

use frontend\widgets\Icon;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Sample Project');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header page-header-actions">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::a(Icon::show('plus') . Yii::t('app', 'Create Sample'), ['/admin/sample/create'], ['class' => 'btn']) ?>
</div>
<?= $this->render('@app/views/sample/view', ['dataProvider' => $dataProvider]) ?>
