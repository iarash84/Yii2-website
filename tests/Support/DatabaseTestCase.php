<?php

namespace tests\Support;

use common\models\User;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\db\Transaction;

abstract class DatabaseTestCase extends TestCase
{
    private Transaction $transaction;

    protected function setUp(): void
    {
        parent::setUp();
        Yii::$app->db->open();
        $this->transaction = Yii::$app->db->beginTransaction();
        Yii::$app->user->logout(false);
        Yii::$app->authManager->invalidateCache();
    }

    protected function tearDown(): void
    {
        Yii::$app->user->logout(false);
        if ($this->transaction->isActive) {
            $this->transaction->rollBack();
        }
        Yii::$app->authManager->invalidateCache();
        parent::tearDown();
    }

    protected function createUser(string $role = 'editor', ?string $username = null): User
    {
        $user = new User();
        $user->username = $username ?: $role . '-' . bin2hex(random_bytes(4));
        $user->email = $user->username . '@example.test';
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword('ValidPassword!2026');
        $user->generateAuthKey();
        self::assertTrue($user->save(), json_encode($user->errors));
        $authRole = Yii::$app->authManager->getRole($role);
        self::assertNotNull($authRole);
        Yii::$app->authManager->assign($authRole, $user->id);
        return $user;
    }
}
