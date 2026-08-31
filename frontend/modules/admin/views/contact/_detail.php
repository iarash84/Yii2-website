<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

?>
<div class="submission-detail">
    <p class="text-overline"><?= Yii::t('app', 'Contact') ?></p>
    <h2><?= Html::encode($model->subject ?: $model->name) ?></h2>
    <?= DetailView::widget(['model' => $model, 'attributes' => ['name', 'phone_number', 'email:email', 'subject', 'body:ntext', ['attribute' => 'created_at', 'format' => 'datetime']]]) ?>
</div>
