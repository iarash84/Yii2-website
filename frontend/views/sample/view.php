<?php

use frontend\widgets\Icon;
use frontend\helpers\MediaUrl;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$models = $dataProvider->models;
?>
<?php if (!$models): ?>
    <section class="card empty-state">
        <?= Icon::show('briefcase', ['class' => 'icon empty-state-icon']) ?>
        <h2><?= Yii::t('app', 'No portfolio items yet') ?></h2>
        <p><?= Yii::t('app', 'Portfolio items will be displayed here after they are added.') ?></p>
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->can('manageContent')): ?>
            <?= Html::a(Icon::show('plus') . Yii::t('app', 'Create Sample'), ['/admin/sample/create'], ['class' => 'btn']) ?>
        <?php endif; ?>
    </section>
<?php else: ?>
    <div class="portfolio-grid">
        <?php foreach ($models as $model): ?>
            <article class="portfolio-card card">
                <div class="portfolio-media">
                    <?= Html::img(MediaUrl::image($model->image, 'img/portfolio/commerce-experience.webp'), [
                        'alt' => Html::encode($model->getLocalized('title')),
                        'loading' => 'lazy',
                        'width' => 768,
                        'height' => 512,
                    ]) ?>
                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->can('manageContent')): ?>
                        <div class="portfolio-admin-actions">
                            <?= Html::a(Icon::show('edit'), ['/admin/sample/update', 'id' => $model->id], [
                                'title' => Yii::t('app', 'Edit'),
                                'aria-label' => Yii::t('app', 'Edit'),
                                'class' => 'icon-button',
                            ]) ?>
                            <?= Html::a(Icon::show('delete'), ['/admin/sample/delete', 'id' => $model->id], [
                                'title' => Yii::t('app', 'Delete item'),
                                'aria-label' => Yii::t('app', 'Delete item'),
                                'class' => 'icon-button icon-button-danger',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="portfolio-body">
                    <h2><?= Html::encode($model->getLocalized('title')) ?></h2>
                    <div class="text-muted"><?= HtmlPurifier::process($model->getLocalized('content')) ?></div>
                    <?php if ($model->link_url): ?>
                        <?= Html::a(
                            Html::encode($model->link_label ?: Yii::t('app', 'View project')) . Icon::show('external'),
                            $model->link_url,
                            ['class' => 'portfolio-link', 'rel' => 'noopener noreferrer']
                        ) ?>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
