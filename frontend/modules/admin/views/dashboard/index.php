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
    ['pages', 'pages', Yii::t('app', 'Dynamic pages'), ['/admin/page/index']],
    ['image', 'media', Yii::t('app', 'Media library'), ['/admin/media/index']],
    ['help', 'faqs', Yii::t('app', 'FAQ management'), ['/admin/faqs/index']],
    ['users', 'users', Yii::t('app', 'Users'), ['/admin/user/index']],
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
<div class="dashboard-detail-grid">
    <section class="card">
        <div class="dashboard-section-title"><h2><?= Yii::t('app', 'Latest posts') ?></h2><?= Html::a(Yii::t('app', 'View all'), ['/admin/blog/index']) ?></div>
        <?php if ($latestPosts): ?><ul class="activity-list"><?php foreach ($latestPosts as $post): ?><li><div><strong><?= Html::encode($post->getLocalized('title')) ?></strong><small><?= Html::encode($post->category ? $post->category->getLocalized('title') : '') ?></small></div><time><?= Yii::$app->formatter->asDatetime($post->createDatetime) ?></time></li><?php endforeach; ?></ul><?php else: ?><p class="empty-state"><?= Yii::t('app', 'No posts have been created yet.') ?></p><?php endif; ?>
    </section>
    <?php if (Yii::$app->user->can('viewSubmissions')): ?><section class="card">
        <div class="dashboard-section-title"><h2><?= Yii::t('app', 'Latest contact messages') ?></h2><?= Html::a(Yii::t('app', 'View all'), ['/admin/contact/index']) ?></div>
        <?php if ($latestContacts): ?><ul class="activity-list"><?php foreach ($latestContacts as $contact): ?><li><div><strong><?= Html::encode($contact->subject ?: $contact->name) ?></strong><small><?= Html::encode($contact->email) ?></small></div><time><?= Yii::$app->formatter->asDatetime($contact->createDateTime) ?></time></li><?php endforeach; ?></ul><?php else: ?><p class="empty-state"><?= Yii::t('app', 'There are no contact messages.') ?></p><?php endif; ?>
    </section><?php endif; ?>
</div>
<section class="card system-overview">
    <div><span><?= Yii::t('app', 'Environment') ?></span><strong><?= Html::encode(strtoupper($systemStatus['environment'])) ?></strong></div>
    <div><span><?= Yii::t('app', 'Maintenance mode') ?></span><strong class="status-pill <?= $systemStatus['maintenance'] ? 'is-warning' : 'is-success' ?>"><?= $systemStatus['maintenance'] ? Yii::t('app', 'Enabled') : Yii::t('app', 'Disabled') ?></strong></div>
    <div><span><?= Yii::t('app', 'Active calendar') ?></span><strong><?= $systemStatus['calendar'] === 'jalali' ? Yii::t('app', 'Jalali calendar') : Yii::t('app', 'Gregorian calendar') ?></strong></div>
    <div><span><?= Yii::t('app', 'Current time') ?></span><strong><?= Yii::$app->formatter->asDatetime(time()) ?></strong></div>
</section>
