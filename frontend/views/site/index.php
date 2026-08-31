<?php

use frontend\models\Carousel;
use frontend\models\Setting;
use frontend\models\HomeSection;
use frontend\models\Blog;
use frontend\models\Sample;
use frontend\models\Faqs;
use frontend\helpers\MediaUrl;
use frontend\widgets\Icon;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$siteSettings = new Setting();
$this->title = trim((string) $siteSettings->companyName) ?: Yii::t('app', 'Website');
$slides = Carousel::find()->where(['status' => 1])->orderBy('sort_order')->all();
$home = Setting::findOne(['type' => 'Home']);
$sections = HomeSection::find()->where(['status' => 1])->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])->all();
?>
<section class="hero-slider" data-hero-slider aria-roledescription="carousel" aria-label="<?= Yii::t('app', 'Carousel') ?>">
    <div class="hero-slides">
        <?php foreach ($slides ?: [null] as $index => $slide): ?>
            <article class="hero hero-slide<?= $index === 0 ? ' is-active' : '' ?>" data-hero-slide aria-hidden="<?= $index === 0 ? 'false' : 'true' ?>">
                <?= Html::img(MediaUrl::image($slide ? $slide->image : null, 'img/portfolio/hero-studio.webp'), ['alt' => $slide ? Html::encode($slide->title) : '', 'loading' => $index === 0 ? 'eager' : 'lazy']) ?>
                <div class="hero-content">
                    <p class="text-overline"><?= Yii::t('app', 'Welcome') ?></p>
                    <h<?= $index === 0 ? '1 id="home-hero-title"' : '2' ?>><?= Html::encode($slide && $slide->title ? $slide->title : $this->title) ?></h<?= $index === 0 ? '1' : '2' ?>>
                    <?= $slide && $slide->text ? HtmlPurifier::process($slide->text) : Html::tag('p', Yii::t('app', 'We build reliable digital products for growing businesses.')) ?>
                    <div class="hero-actions">
                        <?php if ($slide && preg_match('~^(https?://|/)~i', (string) $slide->link)): ?><?= Html::a(Yii::t('app', 'View more'), $slide->link, ['class' => 'd-btn d-btn-primary']) ?><?php else: ?><?= Html::a(Yii::t('app', 'Order app'), ['/site/order'], ['class' => 'd-btn d-btn-primary']) ?><?php endif; ?>
                        <?= Html::a(Yii::t('app', 'Contact'), ['/site/contact'], ['class' => 'd-btn d-btn-outline hero-secondary-action']) ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php if (count($slides) > 1): ?><div class="hero-slider-controls"><button class="d-btn d-btn-circle d-btn-ghost" type="button" data-hero-previous aria-label="<?= Yii::t('app', 'Previous slide') ?>"><?= \frontend\widgets\Icon::show('chevron-left') ?></button><div class="hero-slider-dots"><?php foreach ($slides as $index => $slide): ?><button type="button" data-hero-dot="<?= $index ?>" aria-label="<?= Yii::t('app', 'Go to slide {number}', ['number' => $index + 1]) ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"></button><?php endforeach; ?></div><button class="d-btn d-btn-circle d-btn-ghost" type="button" data-hero-next aria-label="<?= Yii::t('app', 'Next slide') ?>"><?= \frontend\widgets\Icon::show('chevron-right') ?></button></div><?php endif; ?>
</section>

<?php foreach ($sections as $section): ?>
<section class="section home-section home-section-<?= Html::encode($section->type) ?>" aria-labelledby="home-section-<?= (int)$section->id ?>">
    <div class="section-heading"><p class="text-overline"><?= Html::encode($section->getLocalized('subtitle')) ?></p><h2 id="home-section-<?= (int)$section->id ?>"><?= Html::encode($section->getLocalized('title')) ?></h2></div>
    <?php if ($section->getLocalized('content')): ?><div class="home-section-intro prose"><?= HtmlPurifier::process($section->getLocalized('content')) ?></div><?php endif; ?>
    <?php if ($section->type === 'features'): ?><div class="card-grid"><article class="card"><span class="card-icon">01</span><h3><?= Yii::t('app','Product strategy') ?></h3></article><article class="card"><span class="card-icon">02</span><h3><?= Yii::t('app','Web development') ?></h3></article><article class="card"><span class="card-icon">03</span><h3><?= Yii::t('app','Ongoing support') ?></h3></article></div>
    <?php elseif ($section->type === 'stats'): ?><div class="home-stats"><div><strong><?= Sample::find()->count() ?></strong><span><?= Yii::t('app','Completed projects') ?></span></div><div><strong><?= Blog::find()->count() ?></strong><span><?= Yii::t('app','Published articles') ?></span></div><div><strong><?= Faqs::find()->where(['status'=>1])->count() ?></strong><span><?= Yii::t('app','Helpful answers') ?></span></div></div>
    <?php elseif ($section->type === 'portfolio'): ?><div class="card-grid"><?php foreach (Sample::find()->orderBy(['id'=>SORT_DESC])->limit(3)->all() as $item): ?><article class="card content-card home-portfolio-card"><div class="home-portfolio-image"><?= Html::img(MediaUrl::image($item->image, 'img/portfolio/commerce-experience.webp'), ['alt' => Html::encode($item->getLocalized('title')), 'loading' => 'lazy']) ?></div><div class="home-portfolio-body"><h3><?= Html::encode($item->getLocalized('title')) ?></h3><div class="content-card-summary"><?= HtmlPurifier::process($item->getLocalized('content')) ?></div></div></article><?php endforeach; ?></div><p class="section-action"><?= Html::a(Yii::t('app','View all'),['/site/sample'],['class'=>'d-btn d-btn-outline']) ?></p>
    <?php elseif ($section->type === 'posts'): ?><div class="card-grid"><?php foreach (Blog::find()->orderBy(['created_at'=>SORT_DESC])->limit(3)->all() as $post): ?><article class="card content-card"><h3><?= Html::a(Html::encode($post->getLocalized('title')), ['/blog/view', 'id' => $post->id]) ?></h3><p class="content-card-summary"><?= Html::encode($post->getLocalized('description')) ?></p></article><?php endforeach; ?></div><p class="section-action"><?= Html::a(Yii::t('app','View all'),['/blog/index'],['class'=>'d-btn d-btn-outline']) ?></p>
    <?php elseif ($section->type === 'faqs'): ?><div class="faq-list"><?php foreach (Faqs::find()->where(['status'=>1])->orderBy(['sort_order'=>SORT_ASC])->limit(4)->all() as $faq): ?><details class="card faq-item"><summary><span><?= Html::encode($faq->getLocalized('question')) ?></span><span class="faq-toggle" aria-hidden="true"><?= Icon::show('chevron-down') ?></span></summary><div class="faq-answer"><?= HtmlPurifier::process($faq->getLocalized('answer')) ?></div></details><?php endforeach; ?></div>
    <?php elseif ($section->type === 'cta'): ?><div class="home-cta-actions"><?= Html::a(Yii::t('app','Contact'),['/site/contact'],['class'=>'d-btn d-btn-primary']) ?><?= Html::a(Yii::t('app','Order app'),['/site/order'],['class'=>'d-btn d-btn-outline']) ?></div><?php endif; ?>
</section>
<?php endforeach; ?>

<?php if ($home !== null && trim((string) $home->getLocalizedContent()) !== ''): ?>
    <section class="card prose" aria-label="<?= Yii::t('app', 'About') ?>">
        <?= HtmlPurifier::process($home->getLocalizedContent()) ?>
    </section>
<?php endif; ?>
