<?php

namespace tests\unit;

use common\validators\PasswordValidator;
use PHPUnit\Framework\TestCase;
use yii\base\DynamicModel;

class PasswordValidatorTest extends TestCase
{
    public function testStrongPasswordIsAccepted(): void
    {
        $model = new DynamicModel(['password' => 'StrongPassword!2026']);
        $model->addRule('password', PasswordValidator::class);
        self::assertTrue($model->validate(), json_encode($model->errors));
    }

    public function testWeakPasswordIsRejected(): void
    {
        $model = new DynamicModel(['password' => 'password']);
        $model->addRule('password', PasswordValidator::class);
        self::assertFalse($model->validate());
    }
}
