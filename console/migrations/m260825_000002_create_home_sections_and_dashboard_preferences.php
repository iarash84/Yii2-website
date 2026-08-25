<?php

use yii\db\Migration;

class m260825_000002_create_home_sections_and_dashboard_preferences extends Migration
{
    public function safeUp()
    {
        $options = $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB' : null;
        $this->createTable('{{%home_section}}', [
            'id' => $this->primaryKey()->unsigned(),
            'type' => $this->string(32)->notNull()->defaultValue('content'),
            'title' => $this->string(255)->notNull(),
            'subtitle' => $this->string(500),
            'content' => $this->text(),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->boolean()->notNull()->defaultValue(true),
            'created_by' => $this->integer()->unsigned(),
            'updated_by' => $this->integer()->unsigned(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $options);
        $this->createIndex('idx-home-section-status-order', '{{%home_section}}', ['status', 'sort_order']);
        $this->addForeignKey('fk-home-section-created-by', '{{%home_section}}', 'created_by', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk-home-section-updated-by', '{{%home_section}}', 'updated_by', '{{%user}}', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('{{%dashboard_preference}}', [
            'user_id' => $this->integer()->unsigned()->notNull(),
            'layout_json' => $this->text()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'PRIMARY KEY ([[user_id]])',
        ], $options);
        $this->addForeignKey('fk-dashboard-preference-user', '{{%dashboard_preference}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%dashboard_preference}}');
        $this->dropTable('{{%home_section}}');
    }
}
