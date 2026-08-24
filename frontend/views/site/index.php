<?php

use frontend\models\Carousel;
use frontend\models\Setting;
use frontend\helpers\MediaUrl;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->title = Yii::t('app', 'My Company');
$slides = Carousel::find()->where(['status' => 1])->orderBy('order_num')->all();
$hero = reset($slides) ?: null;
$home = Setting::findOne(['type' => 'Home']);
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

<section class="section" aria-labelledby="services-title">
    <div class="section-heading">
        <p class="text-overline"><?= Yii::t('app', 'Our services') ?></p>
        <h2 id="services-title"><?= Yii::t('app', 'From idea to a dependable product') ?></h2>
    </div>
    <div class="card-grid">
        <article class="card"><span class="card-icon" aria-hidden="true">01</span><h3><?= Yii::t('app', 'Product strategy') ?></h3><p><?= Yii::t('app', 'Clear planning based on your users and business goals.') ?></p></article>
        <article class="card"><span class="card-icon" aria-hidden="true">02</span><h3><?= Yii::t('app', 'Web development') ?></h3><p><?= Yii::t('app', 'Secure and maintainable implementation for the web.') ?></p></article>
        <article class="card"><span class="card-icon" aria-hidden="true">03</span><h3><?= Yii::t('app', 'Ongoing support') ?></h3><p><?= Yii::t('app', 'Continuous improvement after the initial release.') ?></p></article>
    </div>
</section>

<?php if ($home !== null && trim((string) $home->getLocalizedContent()) !== ''): ?>
    <section class="card prose" aria-label="<?= Yii::t('app', 'About') ?>">
        <?= HtmlPurifier::process($home->getLocalizedContent()) ?>
    </section>
<?php endif; ?>
