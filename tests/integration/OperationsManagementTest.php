<?php

namespace tests\integration;

use frontend\models\AdminAudit;
use frontend\models\Faqs;
use frontend\models\SystemSetting;
use frontend\services\BackupService;
use tests\Support\DatabaseTestCase;
use Yii;

class OperationsManagementTest extends DatabaseTestCase
{
    public function testOperationalPermissions(): void
    {
        $admin = $this->createUser('admin');
        $super = $this->createUser('superAdmin');
        self::assertTrue(Yii::$app->authManager->checkAccess($admin->id, 'viewAudit'));
        self::assertTrue(Yii::$app->authManager->checkAccess($admin->id, 'exportData'));
        self::assertTrue(Yii::$app->authManager->checkAccess($admin->id, 'manageSystem'));
        self::assertFalse(Yii::$app->authManager->checkAccess($admin->id, 'manageBackup'));
        self::assertTrue(Yii::$app->authManager->checkAccess($super->id, 'manageBackup'));
    }

    public function testSecretSettingsAreEncryptedAtRest(): void
    {
        self::assertTrue(SystemSetting::put('smtp_password', 'VerySecret!2026', true));
        $stored = SystemSetting::findOne('smtp_password');
        self::assertNotSame('VerySecret!2026', $stored->value);
        self::assertSame('VerySecret!2026', SystemSetting::getValue('smtp_password'));
    }

    public function testAdminActionCreatesAuditRecord(): void
    {
        $admin = $this->createUser('admin');
        self::assertTrue(Yii::$app->user->login($admin));
        Yii::$app->runAction('admin/audit/index');
        self::assertTrue(AdminAudit::find()->where(['user_id' => $admin->id, 'route' => 'admin/audit/index'])->exists());
    }

    public function testBackupHasValidatedFormat(): void
    {
        $backup = json_decode(BackupService::create(), true);
        self::assertSame('yii2-website-backup', $backup['format']);
        self::assertSame(2, $backup['version']);
        self::assertArrayHasKey('system_setting', $backup['tables']);
        $this->expectException(\RuntimeException::class);
        BackupService::restore('{"format":"invalid"}');
    }

    public function testFaqTranslationPublicationAndOrder(): void
    {
        $second = new Faqs(['question' => 'Second', 'answer' => 'Answer', 'status' => 1, 'sort_order' => 20]);
        $first = new Faqs(['question' => 'First', 'answer' => 'Answer', 'status' => 1, 'sort_order' => 10]);
        $hidden = new Faqs(['question' => 'Hidden', 'answer' => 'Answer', 'status' => 0, 'sort_order' => 0]);
        self::assertTrue($second->save());
        self::assertTrue($first->save());
        self::assertTrue($hidden->save());
        self::assertTrue($first->saveTranslations(['en' => ['question' => 'Translated question']]));
        $public = Faqs::find()->where(['status' => 1])->orderBy(['sort_order' => SORT_ASC])->all();
        self::assertSame($first->id, $public[0]->id);
        Yii::$app->languageManager->activate('en');
        self::assertSame('Translated question', $first->getLocalized('question'));
    }
}
