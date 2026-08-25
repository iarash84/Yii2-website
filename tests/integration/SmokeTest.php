<?php

namespace tests\integration;

use frontend\models\Setting;
use frontend\models\HomeSection;
use frontend\models\Sample;
use tests\Support\DatabaseTestCase;
use Yii;

class SmokeTest extends DatabaseTestCase
{
    public function testDemoImageAssetsExist(): void
    {
        $webroot = Yii::getAlias('@webroot');
        foreach (['analytics-platform.webp', 'mobile-banking.webp', 'commerce-experience.webp', 'hero-studio.webp'] as $file) {
            $path = $webroot . '/img/portfolio/' . $file;
            self::assertFileExists($path);
            self::assertGreaterThan(10_000, filesize($path));
        }
    }

    public function testMainPagesRenderWithoutServerErrors(): void
    {
        self::assertTrue((new HomeSection(['type'=>'portfolio','title'=>'Portfolio','status'=>1]))->save());
        self::assertTrue((new Sample(['title'=>'Sample','content'=>'<p>Sample content</p>']))->save());
        foreach (
            [
            'CompanyName' => 'سایت آزمایشی',
            'About' => '<p>درباره ما</p>',
            'Opportunity' => '<p>همکاری</p>',
            'Home' => '<p>خانه</p>',
            ] as $type => $content
        ) {
            self::assertTrue((new Setting(['type' => $type, 'content' => $content]))->save());
        }

        foreach (['site/index', 'site/about', 'site/contact', 'site/order', 'site/opportunity', 'site/sample', 'site/faqs', 'blog/index'] as $route) {
            Yii::$app->response->clear();
            $output = Yii::$app->runAction($route);
            self::assertIsString($output, "Route did not render: {$route}");
            self::assertNotSame('', trim($output), "Route returned empty output: {$route}");
            self::assertLessThan(500, Yii::$app->response->statusCode, "Route failed: {$route}");
        }
    }

    public function testAdminPagesRenderWithDesignSystemDependencies(): void
    {
        $admin = $this->createUser('superAdmin', 'ui-smoke-admin');
        self::assertTrue(Yii::$app->user->login($admin));

        foreach (
            [
            'admin/dashboard/index',
            'admin/setting/index',
            'admin/setting/about',
            'admin/setting/home',
            'admin/setting/social',
            'admin/setting/system',
            'admin/blog/index',
            'admin/category/index',
            'admin/carousel/index',
            'admin/sample/index',
            'admin/page/index',
            'admin/media/index',
            'admin/menu/index',
            'admin/faqs/index',
            'admin/setting/email',
            'admin/audit/index',
            'admin/export/index',
            'admin/backup/index',
            'admin/contact/index',
            'admin/order/index',
            'admin/opportunity/index',
            'admin/user/index',
            ] as $route
        ) {
            Yii::$app->response->clear();
            $output = Yii::$app->runAction($route);
            self::assertIsString($output, "Admin route did not render: {$route}");
            self::assertNotSame('', trim($output), "Admin route returned empty output: {$route}");
            self::assertLessThan(500, Yii::$app->response->statusCode, "Admin route failed: {$route}");
        }
    }
}
