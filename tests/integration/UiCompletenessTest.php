<?php

namespace tests\integration;

use frontend\models\Setting;
use tests\Support\DatabaseTestCase;
use Yii;

class UiCompletenessTest extends DatabaseTestCase
{
    public function testFaqManagementIsVisibleToEditor(): void
    {
        $editor = $this->createUser('editor', 'faq-editor');
        self::assertTrue(Yii::$app->user->login($editor));
        $output = Yii::$app->runAction('admin/dashboard/index');
        self::assertStringContainsString('/admin/faqs/index', $output);
        self::assertStringContainsString(Yii::t('app', 'FAQ management'), $output);
    }

    public function testConfiguredSocialLinksAreRenderedInFooter(): void
    {
        foreach (['Instagram' => 'https://instagram.com/example', 'Telegram' => 'https://t.me/example'] as $type => $url) {
            self::assertTrue((new Setting(['type' => $type, 'content' => $url]))->save());
        }
        Yii::$app->user->logout(false);
        $output = Yii::$app->runAction('site/index');
        self::assertStringContainsString('https://instagram.com/example', $output);
        self::assertStringContainsString('https://t.me/example', $output);
    }

    public function testEveryLiteralApplicationMessageHasPersianTranslation(): void
    {
        $catalog = require Yii::getAlias('@app/messages/fa_IR/app.php');
        $missing = [];
        foreach (['common', 'frontend', 'console'] as $directory) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(dirname(__DIR__, 2) . '/' . $directory));
            foreach ($iterator as $file) {
                if (!$file->isFile() || $file->getExtension() !== 'php') {
                    continue;
                }
                preg_match_all('/Yii::t\(\s*[\'\"]app[\'\"]\s*,\s*([\'\"])(.*?)\1/s', file_get_contents($file->getPathname()), $matches);
                foreach ($matches[2] as $message) {
                    if (!array_key_exists($message, $catalog)) {
                        $missing[$message] = true;
                    }
                }
            }
        }
        self::assertSame([], array_keys($missing), 'Missing fa_IR translations: ' . implode(', ', array_keys($missing)));
    }

    public function testThemeToggleUsesIconsWithoutVisibleLabel(): void
    {
        Yii::$app->user->logout(false);
        $output = Yii::$app->runAction('site/index');
        self::assertStringContainsString('theme-icon-moon', $output);
        self::assertStringContainsString('theme-icon-sun', $output);
        self::assertStringNotContainsString('data-theme-label', $output);
    }
}
