<?php

namespace tests\integration;

use frontend\models\Setting;
use tests\Support\DatabaseTestCase;
use Yii;

class SmokeTest extends DatabaseTestCase
{
    public function testMainPagesRenderWithoutServerErrors(): void
    {
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
}
