<?php

namespace tests\integration;

use common\models\LoginForm;
use tests\Support\DatabaseTestCase;
use Yii;

class AuthenticationTest extends DatabaseTestCase
{
    public function testUserCanLoginAndLogout(): void
    {
        $user = $this->createUser('editor', 'login-user');
        $form = new LoginForm([
            'username' => $user->username,
            'password' => 'ValidPassword!2026',
            'rememberMe' => false,
        ]);

        self::assertTrue($form->login(), json_encode($form->errors));
        self::assertFalse(Yii::$app->user->isGuest);
        self::assertSame((string) $user->id, (string) Yii::$app->user->id);
        self::assertTrue(Yii::$app->user->logout(false));
        self::assertTrue(Yii::$app->user->isGuest);
    }

    public function testInvalidPasswordCannotLogin(): void
    {
        $user = $this->createUser('editor', 'invalid-login-user');
        $form = new LoginForm(['username' => $user->username, 'password' => 'WrongPassword!2026']);

        self::assertFalse($form->login());
        self::assertTrue(Yii::$app->user->isGuest);
    }

    public function testLogoutRouteOnlyAcceptsPost(): void
    {
        $controller = new \frontend\controllers\SiteController('site', Yii::$app);
        $behaviors = $controller->behaviors();
        self::assertSame(['post'], $behaviors['verbs']['actions']['logout']);
    }
}
