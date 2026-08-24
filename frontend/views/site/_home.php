<?php

use yii\helpers\Html;
?>
<section class="card empty-state">
    <h2><?= Yii::t('app', 'Welcome') ?></h2>
    <p><?= Yii::t('app', 'Homepage content can be managed from the admin panel.') ?></p>
    <?php if (!Yii::$app->user->isGuest): ?>
        <?= Html::a(Yii::t('app', 'Home Update'), ['/admin/setting/home'], ['class' => 'btn']) ?>
    <?php endif; ?>
</section>
