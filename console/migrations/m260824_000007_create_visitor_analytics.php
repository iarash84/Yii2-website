<?php

use yii\db\Migration;

class m260824_000007_create_visitor_analytics extends Migration
{
    public function safeUp()
    {
        $options = $this->db->driverName === 'mysql'
            ? 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB'
            : null;

        $this->createTable('{{%visitor_daily}}', [
            'visit_date' => $this->date()->notNull(),
            'page_views' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'visitors' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'PRIMARY KEY ([[visit_date]])',
        ], $options);
        $this->createTable('{{%visitor_country_daily}}', [
            'visit_date' => $this->date()->notNull(),
            'country_code' => $this->string(2)->notNull(),
            'page_views' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'visitors' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'PRIMARY KEY ([[visit_date]], [[country_code]])',
        ], $options);
        $this->createTable('{{%visitor_page_daily}}', [
            'visit_date' => $this->date()->notNull(),
            'path' => $this->string(500)->notNull(),
            'page_views' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'visitors' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
            'PRIMARY KEY ([[visit_date]], [[path]])',
        ], $options);
        $this->createTable('{{%visitor_unique}}', [
            'visit_date' => $this->date()->notNull(),
            'visitor_hash' => $this->char(64)->notNull(),
            'dimension_type' => $this->string(12)->notNull(),
            'dimension_value' => $this->string(500)->notNull(),
            'PRIMARY KEY ([[visit_date]], [[visitor_hash]], [[dimension_type]], [[dimension_value]])',
        ], $options);
        $this->createIndex('idx-visitor-daily-views', '{{%visitor_daily}}', ['visit_date', 'page_views']);
        $this->createIndex('idx-visitor-country-views', '{{%visitor_country_daily}}', ['visit_date', 'page_views']);
        $this->createIndex('idx-visitor-page-views', '{{%visitor_page_daily}}', ['visit_date', 'page_views']);

        $auth = Yii::$app->authManager;
        if ($auth->getPermission('viewAnalytics') === null) {
            $permission = $auth->createPermission('viewAnalytics');
            $permission->description = 'مشاهده آمار تجمیعی بازدیدکنندگان';
            $auth->add($permission);
        }
        $admin = $auth->getRole('admin');
        $permission = $auth->getPermission('viewAnalytics');
        if ($admin !== null && !$auth->hasChild($admin, $permission)) {
            $auth->addChild($admin, $permission);
        }
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        if (($permission = $auth->getPermission('viewAnalytics')) !== null) {
            $auth->remove($permission);
        }
        $this->dropTable('{{%visitor_unique}}');
        $this->dropTable('{{%visitor_page_daily}}');
        $this->dropTable('{{%visitor_country_daily}}');
        $this->dropTable('{{%visitor_daily}}');
    }
}
