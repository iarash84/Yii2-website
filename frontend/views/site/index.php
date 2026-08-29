<?php

use frontend\models\Carousel;
use frontend\models\Setting;
use frontend\models\HomeSection;
use frontend\models\Blog;
use frontend\models\Sample;
use frontend\models\Faqs;
use frontend\helpers\MediaUrl;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$siteSettings = new Setting();
$this->title = trim((string) $siteSettings->companyName) ?: Yii::t('app', 'Website');
$slides = Carousel::find()->where(['status' => 1])->orderBy('sort_order')->all();
$hero = reset($slides) ?: null;
$home = Setting::findOne(['type' => 'Home']);
$sections = HomeSection::find()->where(['status' => 1])->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])->all();
?>
<section class="hero" aria-labelledby="home-hero-title">
    <?php if ($hero !== null): ?>
        <?= Html::img(MediaUrl::image($hero->image, 'img/portfolio/hero-studio.webp'), ['alt' => '', 'loading' => 'eager']) ?>
    <?php endif; ?>
    <div class="hero-content">
        <p class="text-overline"><?= Yii::t('app', 'Welcome') ?></p>
        <h1 id="home-hero-title"><?= Html::encode($hero && $hero->title ? $hero->title : $this->title) ?></h1>
        <?php if ($hero && $hero->text): ?>
            <?= HtmlPurifier::process($hero->text) ?>
        <?php else: ?>
            <p><?= Yii::t('app', 'We build reliable digital products for growing businesses.') ?></p>
        <?php endif; ?>
        <div class="hero-actions">
            <?= Html::a(Yii::t('app', 'Order app'), ['/site/order'], ['class' => 'btn']) ?>
            <?= Html::a(Yii::t('app', 'Contact'), ['/site/contact'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>
</section>

<?php foreach ($sections as $section): ?>
<section class="section home-section home-section-<?= Html::encode($section->type) ?>" aria-labelledby="home-section-<?= (int)$section->id ?>">
    <div class="section-heading"><p class="text-overline"><?= Html::encode($section->getLocalized('subtitle')) ?></p><h2 id="home-section-<?= (int)$section->id ?>"><?= Html::encode($section->getLocalized('title')) ?></h2></div>
    <?php if ($section->getLocalized('content')): ?><div class="home-section-intro prose"><?= HtmlPurifier::process($section->getLocalized('content')) ?></div><?php endif; ?>
    <?php if ($section->type === 'features'): ?><div class="card-grid"><article class="card"><span class="card-icon">01</span><h3><?= Yii::t('app','Product strategy') ?></h3></article><article class="card"><span class="card-icon">02</span><h3><?= Yii::t('app','Web development') ?></h3></article><article class="card"><span class="card-icon">03</span><h3><?= Yii::t('app','Ongoing support') ?></h3></article></div>
    <?php elseif ($section->type === 'stats'): ?><div class="home-stats"><div><strong><?= Sample::find()->count() ?></strong><span><?= Yii::t('app','Completed projects') ?></span></div><div><strong><?= Blog::find()->count() ?></strong><span><?= Yii::t('app','Published articles') ?></span></div><div><strong><?= Faqs::find()->where(['status'=>1])->count() ?></strong><span><?= Yii::t('app','Helpful answers') ?></span></div></div>
    <?php elseif ($section->type === 'portfolio'): ?><div class="card-grid"><?php foreach (Sample::find()->orderBy(['id'=>SORT_DESC])->limit(3)->all() as $item): ?><article class="card"><h3><?= Html::encode($item->getLocalized('title')) ?></h3><div class="prose"><?= HtmlPurifier::process($item->getLocalized('content')) ?></div></article><?php endforeach; ?></div><p><?= Html::a(Yii::t('app','View all'),['/site/sample'],['class'=>'btn btn-secondary']) ?></p>
    <?php elseif ($section->type === 'posts'): ?><div class="card-grid"><?php foreach (Blog::find()->orderBy(['created_at'=>SORT_DESC])->limit(3)->all() as $post): ?><article class="card"><h3><?= Html::encode($post->getLocalized('title')) ?></h3><p><?= Html::encode($post->getLocalized('description')) ?></p></article><?php endforeach; ?></div><?= Html::a(Yii::t('app','View all'),['/blog/index'],['class'=>'btn btn-secondary']) ?>
    <?php elseif ($section->type === 'faqs'): ?><div class="faq-list"><?php foreach (Faqs::find()->where(['status'=>1])->orderBy(['sort_order'=>SORT_ASC])->limit(4)->all() as $faq): ?><details class="card"><summary><?= Html::encode($faq->getLocalized('question')) ?></summary><div><?= HtmlPurifier::process($faq->getLocalized('answer')) ?></div></details><?php endforeach; ?></div>
    <?php elseif ($section->type === 'cta'): ?><div class="home-cta-actions"><?= Html::a(Yii::t('app','Contact'),['/site/contact'],['class'=>'btn']) ?><?= Html::a(Yii::t('app','Order app'),['/site/order'],['class'=>'btn btn-secondary']) ?></div><?php endif; ?>
</section>
<?php endforeach; ?>

<?php if ($home !== null && trim((string) $home->getLocalizedContent()) !== ''): ?>
    <section class="card prose" aria-label="<?= Yii::t('app', 'About') ?>">
        <?= HtmlPurifier::process($home->getLocalizedContent()) ?>
    </section>
<?php endif; ?>
