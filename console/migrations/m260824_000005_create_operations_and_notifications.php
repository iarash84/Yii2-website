<?php

use yii\db\Migration;

class m260824_000005_create_operations_and_notifications extends Migration
{
    public function safeUp()
    {
        $options = $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB' : null;
        $this->createTable('{{%system_setting}}', [
            'key' => $this->string(100)->notNull(), 'value' => $this->text(), 'is_secret' => $this->boolean()->notNull()->defaultValue(false),
            'updated_by' => $this->integer()->unsigned(), 'created_at' => $this->integer()->notNull(), 'updated_at' => $this->integer()->notNull(),
            'PRIMARY KEY ([[key]])',
        ], $options);
        $this->addForeignKey('fk-system-setting-user', '{{%system_setting}}', 'updated_by', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
        $this->createTable('{{%admin_audit}}', [
            'id' => $this->bigPrimaryKey()->unsigned(), 'user_id' => $this->integer()->unsigned(), 'route' => $this->string(180)->notNull(),
            'action' => $this->string(80)->notNull(), 'method' => $this->string(10)->notNull(), 'details' => $this->text(),
            'ip' => $this->string(45), 'user_agent' => $this->string(500), 'created_at' => $this->integer()->notNull(),
        ], $options);
        $this->createIndex('idx-admin-audit-user-created', '{{%admin_audit}}', ['user_id', 'created_at']);
        $this->createIndex('idx-admin-audit-route-created', '{{%admin_audit}}', ['route', 'created_at']);
        $this->addForeignKey('fk-admin-audit-user', '{{%admin_audit}}', 'user_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
        $this->addColumn('{{%tbl_faqs}}', 'status', $this->boolean()->notNull()->defaultValue(true));
        $this->addColumn('{{%tbl_faqs}}', 'sort_order', $this->integer()->notNull()->defaultValue(0));
        $this->createIndex('idx-faq-status-order', '{{%tbl_faqs}}', ['status', 'sort_order']);

        $auth = Yii::$app->authManager;
        foreach (['viewAudit' => 'مشاهده گزارش فعالیت مدیران', 'exportData' => 'خروجی داده‌ها', 'manageSystem' => 'مدیریت عملیات سیستم', 'manageBackup' => 'مدیریت پشتیبان‌گیری'] as $name => $description) {
            if ($auth->getPermission($name) === null) {
                $permission = $auth->createPermission($name);
                $permission->description = $description;
                $auth->add($permission);
            }
        }
        $admin = $auth->getRole('admin');
        foreach (['viewAudit', 'exportData', 'manageSystem'] as $name) {
            $permission = $auth->getPermission($name);
            if ($admin && !$auth->hasChild($admin, $permission)) {
                $auth->addChild($admin, $permission);
            }
        }
        $super = $auth->getRole('superAdmin');
        $backup = $auth->getPermission('manageBackup');
        if ($super && !$auth->hasChild($super, $backup)) {
            $auth->addChild($super, $backup);
        }
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        foreach (['viewAudit', 'exportData', 'manageSystem', 'manageBackup'] as $name) {
            if (($permission = $auth->getPermission($name)) !== null) {
                $auth->remove($permission);
            }
        }
        $this->dropColumn('{{%tbl_faqs}}', 'sort_order');
        $this->dropColumn('{{%tbl_faqs}}', 'status');
        $this->dropTable('{{%admin_audit}}');
        $this->dropTable('{{%system_setting}}');
    }
}
