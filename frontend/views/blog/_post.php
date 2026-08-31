<?php
use yii\helpers\Html;
use frontend\widgets\Icon;

?>
<article class="blog-post content-card">
        <?php $localizedTitle = $model->getLocalized('title'); ?>
        <h2><?= Html::a(Html::encode($localizedTitle), ['blog/view', 'id' => $model->id, 'subject' => str_replace(' ', '_', trim($localizedTitle))]) ?></h2>

        <div class="article-meta">
            <span>
                <?= Icon::show('users') ?> <?= Html::encode($model->user->username) ?>
            </span>
            <span>
                <time datetime="<?= Html::encode($model->created_at) ?>"><?= Yii::$app->formatter->asDatetime($model->created_at) ?></time>
            </span>
        </div>
        <div class="content-card-summary"><?= \yii\helpers\HtmlPurifier::process($model->getLocalized('description')) ?></div>
        <?= Html::a(Yii::t('app', 'continue reading...') . Icon::show('chevron-right'), ['blog/view', 'id' => $model->id, 'subject' => str_replace(' ', '_', trim($localizedTitle))], ['class' => 'read-more-link']) ?>
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->can('manageContent')): ?><div class="blog-card-actions"><?= Html::a(Icon::show('edit') . Yii::t('app', 'Update'), ['/admin/blog/update', 'id' => $model->id], ['class' => 'd-btn d-btn-sm d-btn-outline']) ?><?= Html::a(Icon::show('trash') . Yii::t('app', 'Delete'), ['/admin/blog/delete', 'id' => $model->id], ['class' => 'd-btn d-btn-sm d-btn-error d-btn-soft', 'data-method' => 'post', 'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?')]) ?></div><?php endif; ?>
</article>
