<?php

use frontend\widgets\Icon;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Dashboard');
$cards = [
    ['posts', 'posts', Yii::t('app', 'Blog'), ['/admin/blog/index']],
    ['briefcase', 'samples', Yii::t('app', 'Sample Project'), ['/admin/sample/index']],
    ['inbox', 'contacts', Yii::t('app', 'Contact'), ['/admin/contact/index']],
    ['posts', 'orders', Yii::t('app', 'Order app'), ['/admin/order/index']],
    ['users', 'opportunities', Yii::t('app', 'Job opportunity'), ['/admin/opportunity/index']],
];
?>
<div class="page-header page-header-actions">
    <div><p class="text-overline"><?= Yii::t('app', 'Admin panel') ?></p><h1><?= Html::encode($this->title) ?></h1></div>
    <?= Html::a(Icon::show('plus') . Yii::t('app', 'Create Post'), ['/admin/blog/create'], ['class' => 'btn']) ?>
</div>
<div class="metric-grid">
    <?php foreach ($cards as [$icon, $key, $label, $url]): ?>
        <?php if ($counts[$key] === null) {
            continue;
        } ?>
        <?= Html::a(
            Icon::show($icon, ['class' => 'icon metric-icon'])
            . Html::tag('strong', (string) $counts[$key], ['class' => 'metric-value'])
            . Html::tag('span', $label, ['class' => 'metric-label']),
            $url,
            ['class' => 'metric-card']
        ) ?>
    <?php endforeach; ?>
</div>
<section class="card section-shortcut">
    <div><h2><?= Yii::t('app', 'Quick actions') ?></h2><p class="text-muted"><?= Yii::t('app', 'Common management tasks are available here.') ?></p></div>
    <div class="action-row">
        <?= Html::a(Icon::show('plus') . Yii::t('app', 'Create Post'), ['/admin/blog/create'], ['class' => 'btn']) ?>
        <?= Html::a(Icon::show('plus') . Yii::t('app', 'Create Sample'), ['/admin/sample/create'], ['class' => 'btn btn-secondary']) ?>
        <?php if (Yii::$app->user->can('manageSettings')): ?>
            <?= Html::a(Icon::show('settings') . Yii::t('app', 'Setting'), ['/admin/setting/index'], ['class' => 'btn btn-secondary']) ?>
        <?php endif; ?>
    </div>
</section>
