<?php

use frontend\widgets\Icon;
use yii\helpers\Html;

$options = [
    ['system', 'settings', Yii::t('app', 'System theme')],
    ['site-light', 'sun', Yii::t('app', 'Site light')],
    ['site-dark', 'moon', Yii::t('app', 'Site dark')],
    ['corporate', 'briefcase', 'Corporate'],
    ['nord', 'image', 'Nord'],
    ['business', 'dashboard', 'Business'],
];
?>
<div class="appearance-control<?= !empty($inSidebar) ? ' appearance-control-sidebar' : '' ?>">
    <details>
        <summary class="d-btn <?= !empty($inSidebar) ? 'd-btn-ghost appearance-sidebar-summary' : 'd-btn-square d-btn-ghost' ?>" aria-label="<?= Yii::t('app', 'Color theme') ?>"><?= Icon::show('sun') ?><?php if (!empty($inSidebar)): ?><span><?= Yii::t('app', 'Color theme') ?></span><?php endif; ?></summary>
        <div class="appearance-menu" role="group" aria-label="<?= Yii::t('app', 'Color theme') ?>">
            <?php foreach ($options as [$themeValue, $themeIcon, $themeLabel]): ?><button class="d-btn d-btn-sm d-btn-ghost" type="button" data-theme-option="<?= Html::encode($themeValue) ?>" title="<?= Html::encode($themeLabel) ?>" aria-label="<?= Html::encode($themeLabel) ?>"><?= Icon::show($themeIcon) ?><span><?= Html::encode($themeLabel) ?></span></button><?php endforeach; ?>
        </div>
    </details>
</div>
