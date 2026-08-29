<?php

use common\widgets\Alert;
use frontend\assets\AppAsset;
use frontend\models\Setting;
use frontend\models\MenuItem;
use frontend\widgets\Icon;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
$languageManager = Yii::$app->languageManager;
$isRtl = $languageManager->isRtl();
$settings = new Setting();
$socialLinks = $settings->socialLinks;
$siteName = trim((string) $settings->companyName) ?: Yii::t('app', 'Website');
$route = Yii::$app->controller->route;
$isAdmin = Yii::$app->controller->module !== null
    && Yii::$app->controller->module->id === 'admin';
$current = static function ($routePrefix) use ($route) {
    return strpos($route, $routePrefix) === 0 ? 'page' : null;
};
$mainMenu = $isAdmin ? [] : MenuItem::activeRoots('main');
$footerMenu = $isAdmin ? [] : MenuItem::activeRoots('footer');

$this->registerMetaTag(['name' => 'content-language', 'content' => $languageManager->getLocale()]);
foreach ($languageManager->languages as $code => $language) {
    $this->registerLinkTag([
        'rel' => 'alternate',
        'hreflang' => $language['locale'],
        'href' => $languageManager->getLanguageUrl($code),
    ]);
}
$this->registerLinkTag([
    'rel' => 'alternate',
    'hreflang' => 'x-default',
    'href' => $languageManager->getLanguageUrl($languageManager->defaultLanguage),
]);
$this->registerLinkTag([
    'rel' => 'canonical',
    'href' => $this->params['canonicalUrl'] ?? $languageManager->getLanguageUrl($languageManager->activeLanguage),
]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Html::encode($languageManager->getLocale()) ?>" dir="<?= $isRtl ? 'rtl' : 'ltr' ?>" data-theme="light">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#6757d1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="site-shell">
    <a class="skip-link" href="#main-content"><?= Yii::t('app', 'Skip to main content') ?></a>
    <header class="site-header">
        <div class="container header-row">
            <?= Html::a(
                Html::tag('span', 'B', ['class' => 'brand-mark', 'aria-hidden' => 'true'])
                . Html::tag('span', Html::encode($siteName)),
                ['/site/index'],
                ['class' => 'brand', 'aria-label' => Yii::t('app', 'Home')]
            ) ?>
            <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false"
                    aria-controls="primary-navigation" aria-label="<?= Yii::t('app', 'Open menu') ?>">☰</button>
            <nav id="primary-navigation" class="primary-nav" data-primary-nav
                 aria-label="<?= Yii::t('app', 'Main navigation') ?>">
                <ul class="nav-list">
                    <?php if (!$isAdmin): ?>
                        <?php if ($mainMenu): ?>
                            <?php foreach ($mainMenu as $menuItem): ?>
                                <li class="<?= $menuItem->children ? 'nav-menu' : '' ?>">
                                    <?php if ($menuItem->children): ?>
                                        <details>
                                            <summary class="nav-summary"><?= Html::encode($menuItem->getLocalized('label')) ?></summary>
                                            <ul class="nav-submenu">
                                                <?php foreach ($menuItem->children as $child): ?>
                                                    <li><?= Html::a(Html::encode($child->getLocalized('label')), $child->getPublicUrl(), ['target' => $child->target, 'rel' => $child->target === '_blank' ? 'noopener noreferrer' : null]) ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </details>
                                    <?php else: ?>
                                        <?= Html::a(Html::encode($menuItem->getLocalized('label')), $menuItem->getPublicUrl(), ['target' => $menuItem->target, 'rel' => $menuItem->target === '_blank' ? 'noopener noreferrer' : null]) ?>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><?= Html::a(Yii::t('app', 'Home'), ['/site/index'], ['aria-current' => $route === 'site/index' ? 'page' : null]) ?></li>
                            <li><?= Html::a(Yii::t('app', 'Blog'), ['/blog/index'], ['aria-current' => $current('blog/')]) ?></li>
                            <li><?= Html::a(Yii::t('app', 'Sample Project'), ['/site/sample'], ['aria-current' => $current('site/sample')]) ?></li>
                            <li><?= Html::a(Yii::t('app', 'About'), ['/site/about'], ['aria-current' => $current('site/about')]) ?></li>
                            <li><?= Html::a(Yii::t('app', 'Contact'), ['/site/contact'], ['aria-current' => $current('site/contact')]) ?></li>
                            <li><?= Html::a(Yii::t('app', 'Order app'), ['/site/order'], ['class' => 'btn']) ?></li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (!$isAdmin): ?>
                        <li><?= Html::a(Icon::show('posts') . Yii::t('app', 'Search'), ['/search/index']) ?></li>
                        <li><button class="theme-toggle" type="button" data-theme-toggle aria-label="<?= Yii::t('app', 'Switch color theme') ?>"><span class="theme-icon theme-icon-moon"><?= Icon::show('moon') ?></span><span class="theme-icon theme-icon-sun"><?= Icon::show('sun') ?></span></button></li>
                    <?php endif; ?>
                    <?php if (Yii::$app->user->isGuest): ?>
                        <li><?= Html::a(Yii::t('app', 'Login'), ['/site/login']) ?></li>
                    <?php else: ?>
                        <li class="nav-menu">
                            <details>
                                <summary class="nav-summary"><?= Html::encode(Yii::$app->user->identity->username) ?></summary>
                                <ul class="nav-submenu">
                                    <li><?= Html::a(Yii::t('app', 'Admin panel'), ['/admin']) ?></li>
                                    <li><?= Html::a(Yii::t('app', 'Change Password'), ['/admin/user/change']) ?></li>
                                    <li><?= Html::a(Yii::t('app', 'Logout'), ['/site/logout'], ['data-method' => 'post']) ?></li>
                                </ul>
                            </details>
                        </li>
                    <?php endif; ?>
                    <?php if (!$isAdmin): ?>
                        <li class="language-nav" aria-label="<?= Yii::t('app', 'Language') ?>">
                            <?php foreach ($languageManager->languages as $code => $language): ?>
                                <?= Html::a(Html::encode($language['label']), $languageManager->getLanguageUrl($code), [
                                    'lang' => $language['locale'],
                                    'hreflang' => $language['locale'],
                                    'class' => $code === $languageManager->activeLanguage ? 'active' : null,
                                    'aria-current' => $code === $languageManager->activeLanguage ? 'true' : null,
                                ]) ?>
                            <?php endforeach; ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main id="main-content" class="site-main" tabindex="-1">
        <div class="container <?= $isAdmin ? 'admin-shell' : 'content-shell' ?>">
            <?php if ($isAdmin): ?>
                <aside class="admin-sidebar card" aria-label="<?= Yii::t('app', 'Admin panel') ?>">
                    <h2><?= Yii::t('app', 'Admin panel') ?></h2>
                    <ul class="admin-nav">
                        <li><?= Html::a(Icon::show('dashboard') . Yii::t('app', 'Dashboard'), ['/admin'], ['aria-current' => $current('admin/dashboard')]) ?></li>
                        <?php if (Yii::$app->user->can('manageContent') || Yii::$app->user->can('manageMenus') || Yii::$app->user->can('managePages') || Yii::$app->user->can('manageMedia')): ?>
                            <li class="admin-nav-label"><?= Yii::t('app', 'Site structure') ?></li>
                            <?php if (Yii::$app->user->can('manageContent')): ?>
                                <li><?= Html::a(Icon::show('home') . Yii::t('app', 'Homepage sections'), ['/admin/home-section/index'], ['aria-current' => $current('admin/home-section')]) ?></li>
                                <li><?= Html::a(Icon::show('image') . Yii::t('app', 'Carousel'), ['/admin/carousel/index'], ['aria-current' => $current('admin/carousel')]) ?></li>
                            <?php endif; ?>
                            <?php if (Yii::$app->user->can('manageMenus')): ?>
                                <li><?= Html::a(Icon::show('menu') . Yii::t('app', 'Menu management'), ['/admin/menu/index'], ['aria-current' => $current('admin/menu')]) ?></li>
                            <?php endif; ?>
                            <?php if (Yii::$app->user->can('managePages')): ?>
                                <li><?= Html::a(Icon::show('pages') . Yii::t('app', 'Dynamic pages'), ['/admin/page/index'], ['aria-current' => $current('admin/page')]) ?></li>
                            <?php endif; ?>
                            <?php if (Yii::$app->user->can('manageMedia')): ?>
                                <li><?= Html::a(Icon::show('image') . Yii::t('app', 'Media library'), ['/admin/media/index'], ['aria-current' => $current('admin/media')]) ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('manageContent')): ?>
                            <li class="admin-nav-label"><?= Yii::t('app', 'Content') ?></li>
                            <li><?= Html::a(Icon::show('posts') . Yii::t('app', 'Blog'), ['/admin/blog/index'], ['aria-current' => $current('admin/blog')]) ?></li>
                            <li><?= Html::a(Icon::show('tag') . Yii::t('app', 'Category'), ['/admin/category/index'], ['aria-current' => $current('admin/category')]) ?></li>
                            <li><?= Html::a(Icon::show('briefcase') . Yii::t('app', 'Sample Project'), ['/admin/sample/index'], ['aria-current' => $current('admin/sample')]) ?></li>
                            <li><?= Html::a(Icon::show('help') . Yii::t('app', 'FAQ management'), ['/admin/faqs/index'], ['aria-current' => $current('admin/faqs')]) ?></li>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('viewSubmissions')): ?>
                            <li class="admin-nav-label"><?= Yii::t('app', 'Requests') ?></li>
                            <li><?= Html::a(Icon::show('inbox') . Yii::t('app', 'Contact'), ['/admin/contact/index'], ['aria-current' => $current('admin/contact')]) ?></li>
                            <li><?= Html::a(Icon::show('briefcase') . Yii::t('app', 'Order app'), ['/admin/order/index'], ['aria-current' => $current('admin/order')]) ?></li>
                            <li><?= Html::a(Icon::show('users') . Yii::t('app', 'Job opportunity'), ['/admin/opportunity/index'], ['aria-current' => $current('admin/opportunity')]) ?></li>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('manageSettings') || Yii::$app->user->can('manageSystem')): ?>
                            <li class="admin-nav-label"><?= Yii::t('app', 'Site settings') ?></li>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('manageSettings')): ?>
                            <li><?= Html::a(Icon::show('settings') . Yii::t('app', 'General settings'), ['/admin/setting/index'], ['aria-current' => $route === 'admin/setting/index' ? 'page' : null]) ?></li>
                            <li><?= Html::a(Icon::show('pages') . Yii::t('app', 'About'), ['/admin/setting/about'], ['aria-current' => $route === 'admin/setting/about' ? 'page' : null]) ?></li>
                            <li><?= Html::a(Icon::show('external') . Yii::t('app', 'Social Network'), ['/admin/setting/social'], ['aria-current' => $route === 'admin/setting/social' ? 'page' : null]) ?></li>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('manageSystem')): ?>
                            <li><?= Html::a(Icon::show('activity') . Yii::t('app', 'System'), ['/admin/setting/system'], ['aria-current' => $route === 'admin/setting/system' ? 'page' : null]) ?></li>
                            <li><?= Html::a(Icon::show('mail') . Yii::t('app', 'Email settings'), ['/admin/setting/email'], ['aria-current' => $route === 'admin/setting/email' ? 'page' : null]) ?></li>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('viewAudit') || Yii::$app->user->can('exportData') || Yii::$app->user->can('manageBackup') || Yii::$app->user->can('manageUsers')): ?>
                            <li class="admin-nav-label"><?= Yii::t('app', 'Administration') ?></li>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('viewAudit')): ?><li><?= Html::a(Icon::show('activity') . Yii::t('app', 'Admin activity'), ['/admin/audit/index'], ['aria-current' => $current('admin/audit')]) ?></li><?php endif; ?>
                        <?php if (Yii::$app->user->can('exportData')): ?><li><?= Html::a(Icon::show('arrow-down') . Yii::t('app', 'Data export'), ['/admin/export/index'], ['aria-current' => $current('admin/export')]) ?></li><?php endif; ?>
                        <?php if (Yii::$app->user->can('manageBackup')): ?><li><?= Html::a(Icon::show('database') . Yii::t('app', 'Backup and restore'), ['/admin/backup/index'], ['aria-current' => $current('admin/backup')]) ?></li><?php endif; ?>
                        <?php if (Yii::$app->user->can('manageUsers')): ?>
                            <li><?= Html::a(Icon::show('users') . Yii::t('app', 'User Management'), ['/admin/user/index'], ['aria-current' => $current('admin/user')]) ?></li>
                        <?php endif; ?>
                    </ul>
                </aside>
                <section class="admin-content">
            <?php else: ?>
                <section>
            <?php endif; ?>
                    <?= Breadcrumbs::widget([
                        'links' => $this->params['breadcrumbs'] ?? [],
                        'options' => ['class' => 'breadcrumbs', 'aria-label' => Yii::t('app', 'Breadcrumb')],
                        'homeLink' => ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']],
                    ]) ?>
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </section>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <section>
                    <h2><?= Html::encode($siteName) ?></h2>
                    <p><?= Html::encode($settings->address) ?></p>
                    <p class="ltr"><?= Html::encode($settings->email) ?><br><?= Html::encode($settings->phoneNumber) ?></p>
                    <?php if ($socialLinks): ?><ul class="social-links" aria-label="<?= Yii::t('app', 'Social Network') ?>"><?php foreach ($socialLinks as $network => $url): ?><li><?= Html::a(Icon::show(strtolower($network)) . Html::tag('span', Html::encode(Yii::t('app', $network)), ['class' => 'sr-only']), $url, ['class' => 'social-link', 'target' => '_blank', 'rel' => 'noopener noreferrer', 'aria-label' => Yii::t('app', $network)]) ?></li><?php endforeach; ?></ul><?php endif; ?>
                </section>
                <nav aria-label="<?= Yii::t('app', 'Useful links') ?>">
                    <h2><?= Yii::t('app', 'Useful links') ?></h2>
                    <ul class="footer-links">
                        <?php if ($footerMenu): ?>
                            <?php foreach ($footerMenu as $menuItem): ?>
                                <li><?= Html::a(Html::encode($menuItem->getLocalized('label')), $menuItem->getPublicUrl(), ['target' => $menuItem->target, 'rel' => $menuItem->target === '_blank' ? 'noopener noreferrer' : null]) ?></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><?= Html::a(Yii::t('app', 'About'), ['/site/about']) ?></li>
                            <li><?= Html::a(Yii::t('app', 'Contact'), ['/site/contact']) ?></li>
                            <li><?= Html::a(Yii::t('app', 'Blog'), ['/blog/index']) ?></li>
                            <li><?= Html::a(Yii::t('app', 'FAQS'), ['/site/faqs']) ?></li>
                            <li><?= Html::a(Yii::t('app', 'Order app'), ['/site/order']) ?></li>
                            <li><?= Html::a(Yii::t('app', 'Job opportunity'), ['/site/opportunity']) ?></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <div class="footer-bottom">&copy; <?= Yii::$app->formatter->asYear(time()) ?> <?= Html::encode($siteName) ?></div>
        </div>
    </footer>
</div>
<button id="scroll-to-top" type="button" aria-label="<?= Yii::t('app', 'Scroll to top') ?>">↑</button>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
