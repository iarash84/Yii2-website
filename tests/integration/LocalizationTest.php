<?php

namespace tests\integration;

use frontend\models\Category;
use tests\Support\DatabaseTestCase;
use Yii;

class LocalizationTest extends DatabaseTestCase
{
    public function testLanguageDirectionAndLocalizedUrls(): void
    {
        $manager = Yii::$app->languageManager;

        $manager->activate('fa');
        self::assertSame('fa_IR', Yii::$app->language);
        self::assertTrue($manager->isRtl());
        self::assertStringStartsWith('/fa/blog', Yii::$app->urlManager->createUrl(['/blog/index']));

        $manager->activate('en');
        self::assertSame('en_US', Yii::$app->language);
        self::assertFalse($manager->isRtl());
        self::assertStringStartsWith('/en/blog', Yii::$app->urlManager->createUrl(['/blog/index']));
        self::assertStringStartsWith('/admin', Yii::$app->urlManager->createUrl(['/admin/setting/index']));
    }

    public function testDatabaseTranslationAndFallback(): void
    {
        $category = new Category(['title' => 'اخبار']);
        self::assertTrue($category->save());
        self::assertTrue($category->saveTranslations([
            'en' => ['title' => 'News'],
        ]));

        Yii::$app->languageManager->activate('en');
        self::assertSame('News', $category->getLocalized('title'));

        Yii::$app->languageManager->activate('fa');
        self::assertSame('اخبار', $category->getLocalized('title'));
        self::assertSame('اخبار', $category->getLocalized('title', 'de'));
    }

    public function testAdminSettingPagesHavePersianTranslations(): void
    {
        Yii::$app->languageManager->activate('fa');

        $messages = [
            'Home Update',
            'Homepage content',
            'System',
            'System tools',
            'Flush cache',
            'Clear assets',
            'Cache flushed',
            'Assets cleared',
        ];

        foreach ($messages as $message) {
            self::assertNotSame($message, Yii::t('app', $message));
        }
    }
}
