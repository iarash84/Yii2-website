<?php

use yii\db\Migration;

class m260824_000003_create_menu_management extends Migration
{
    public function safeUp()
    {
        $options = $this->db->driverName === 'mysql'
            ? 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB'
            : null;

        $this->createTable('{{%menu_item}}', [
            'id' => $this->primaryKey()->unsigned(),
            'parent_id' => $this->integer()->unsigned(),
            'label' => $this->string(120)->notNull(),
            'url' => $this->string(500)->notNull(),
            'location' => $this->string(32)->notNull()->defaultValue('main'),
            'target' => $this->string(16)->notNull()->defaultValue('_self'),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->boolean()->notNull()->defaultValue(true),
            'created_by' => $this->integer()->unsigned(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $options);
        $this->createIndex('idx-menu-item-location-status-order', '{{%menu_item}}', ['location', 'status', 'sort_order']);
        $this->createIndex('idx-menu-item-parent', '{{%menu_item}}', 'parent_id');
        $this->addForeignKey('fk-menu-item-parent', '{{%menu_item}}', 'parent_id', '{{%menu_item}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-menu-item-user', '{{%menu_item}}', 'created_by', '{{%user}}', 'id', 'SET NULL', 'CASCADE');

        $auth = Yii::$app->authManager;
        if ($auth->getPermission('manageMenus') === null) {
            $permission = $auth->createPermission('manageMenus');
            $permission->description = 'مدیریت منوهای سایت';
            $auth->add($permission);
        }
        $editor = $auth->getRole('editor');
        $permission = $auth->getPermission('manageMenus');
        if ($editor !== null && !$auth->hasChild($editor, $permission)) {
            $auth->addChild($editor, $permission);
        }
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $permission = $auth->getPermission('manageMenus');
        if ($permission !== null) {
            $auth->remove($permission);
        }
        $this->dropForeignKey('fk-menu-item-user', '{{%menu_item}}');
        $this->dropForeignKey('fk-menu-item-parent', '{{%menu_item}}');
        $this->dropTable('{{%menu_item}}');
    }
}
