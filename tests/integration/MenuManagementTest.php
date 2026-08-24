<?php

namespace tests\integration;

use frontend\models\MenuItem;
use tests\Support\DatabaseTestCase;
use Yii;

class MenuManagementTest extends DatabaseTestCase
{
    public function testEditorHasMenuPermission(): void
    {
        $editor = $this->createUser('editor');
        self::assertTrue(Yii::$app->authManager->checkAccess($editor->id, 'manageMenus'));
    }

    public function testMenuSupportsTranslationOrderingAndLocalizedUrl(): void
    {
        $menu = new MenuItem([
            'label' => 'تماس',
            'url' => '/contact',
            'location' => 'main',
            'target' => '_self',
            'sort_order' => 10,
            'status' => 1,
        ]);
        self::assertTrue($menu->save(), json_encode($menu->errors));
        self::assertTrue($menu->saveTranslations(['en' => ['label' => 'Contact']]));

        Yii::$app->languageManager->activate('en');
        self::assertSame('Contact', $menu->getLocalized('label'));
        self::assertStringEndsWith('/en/contact', $menu->getPublicUrl());
        self::assertSame($menu->id, MenuItem::activeRoots()[0]->id);
    }

    public function testUnsafeUrlIsRejected(): void
    {
        $menu = new MenuItem(['label' => 'Unsafe', 'url' => 'javascript:alert(1)', 'location' => 'main', 'target' => '_self']);
        self::assertFalse($menu->validate());
        self::assertArrayHasKey('url', $menu->errors);
    }
}
