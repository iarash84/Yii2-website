<?php

namespace tests\integration;

use frontend\modules\admin\Module;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\base\InlineAction;
use yii\web\Controller;

class AdminAccessTest extends TestCase
{
    public function testGuestCannotAccessAnyAdminArea(): void
    {
        Yii::$app->user->logout(false);
        /** @var Module $module */
        $module = Yii::$app->getModule('admin');

        foreach (['setting', 'blog', 'user', 'contact', 'order', 'opportunity', 'carousel', 'sample'] as $id) {
            $controller = new Controller($id, $module);
            $action = new InlineAction('index', $controller, static fn () => null);
            self::assertFalse($module->beforeAction($action), "Guest admin access was allowed for {$id}.");
        }
        self::assertStringContainsString('/login', Yii::$app->response->headers->get('Location'));
    }
}
