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
</article>
