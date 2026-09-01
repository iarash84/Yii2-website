<?php

namespace tests\integration;

use common\widgets\Alert;
use frontend\models\Faqs;
use frontend\models\SystemSetting;
use tests\Support\DatabaseTestCase;
use Yii;

class AdminFormSubmissionTest extends DatabaseTestCase
{
    public function testFaqCanBeCreatedWhenSortOrderIsLeftBlank(): void
    {
        $faq = new Faqs();
        self::assertTrue($faq->load(['Faqs' => [
            'question' => 'How does it work?', 'answer' => 'It works safely.',
            'sort_order' => '', 'status' => '1',
        ]]));
        $faq->user_id = $this->createUser()->id;
        self::assertTrue($faq->save(), json_encode($faq->errors));
        self::assertSame(0, (int) $faq->sort_order);
        self::assertTrue($faq->saveTranslations(['en' => [
            'question' => 'How does it work?', 'answer' => 'It works safely.',
        ]]));
    }

    public function testDateDisplaySettingAndSuccessAlertRenderWithoutError(): void
    {
        $admin = $this->createUser('admin', 'system-form-admin');
        self::assertTrue(Yii::$app->user->login($admin));
        self::assertTrue(SystemSetting::put('date_calendar', 'jalali'));
        Yii::$app->session->setFlash('success', Yii::t('app', 'Date display settings saved.'));
        $html = Alert::widget();
        self::assertStringContainsString(Yii::t('app', 'Date display settings saved.'), $html);
        self::assertSame('jalali', SystemSetting::getValue('date_calendar'));
    }

    public function testDatabaseUsesConsistentSnakeCaseNames(): void
    {
        $legacy = ['tbl_blog_category', 'tbl_blog_post', 'tbl_carousel', 'tbl_contact_us', 'tbl_faqs', 'tbl_log', 'tbl_opportunity', 'tbl_order', 'tbl_sample', 'tbl_setting'];
        foreach ($legacy as $table) {
            self::assertNull(Yii::$app->db->schema->getTableSchema($table, true));
        }
        $tables = ['blog_category', 'blog_post', 'carousel', 'contact_submission', 'faq', 'login_attempt', 'opportunity_submission', 'order_submission', 'portfolio_item', 'site_setting'];
        foreach ($tables as $table) {
            $schema = Yii::$app->db->schema->getTableSchema($table, true);
            self::assertNotNull($schema, "Missing standardized table: {$table}");
            foreach ($schema->columnNames as $column) {
                self::assertMatchesRegularExpression('/^[a-z][a-z0-9_]*$/', $column, "Non-standard column: {$table}.{$column}");
            }
        }
        $siteSetting = Yii::$app->db->schema->getTableSchema('site_setting', true);
        $uniqueTypeIndexes = array_filter(Yii::$app->db->schema->findUniqueIndexes($siteSetting), static function ($columns) {

                return $columns === ['type'];
        });
        self::assertNotEmpty($uniqueTypeIndexes);
    }

    public function testUserProfileFieldsCanBeMassAssignedAndValidated(): void
    {

        $user = $this->createUser('editor', 'old-username');
        self::assertTrue($user->load(['User' => [
            'username' => 'new-username',
            'email' => 'new@example.test',
            'role' => 'editor',
        ]]));
        self::assertTrue($user->validate(), json_encode($user->errors));
        self::assertSame('new-username', $user->username);
        self::assertSame('new@example.test', $user->email);
    }
}
