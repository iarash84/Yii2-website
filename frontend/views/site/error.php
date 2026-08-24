<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="card empty-state site-error" role="alert">
    <span class="error-code" aria-hidden="true"><?= (int) ($exception->statusCode ?? 500) ?></span>
    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= nl2br(Html::encode($message)) ?></p>
    <p class="text-muted"><?= Yii::t('app', 'Please try again or contact us if the problem continues.') ?></p>
    <?= Html::a(Yii::t('app', 'Back to home'), ['/site/index'], ['class' => 'btn']) ?>
</div>
