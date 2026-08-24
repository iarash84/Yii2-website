<?php

use yii\db\Migration;

class m260824_000004_create_pages_media_and_seo extends Migration
{
    public function safeUp()
    {
        $options = $this->db->driverName === 'mysql'
            ? 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB'
            : null;

        $this->createTable('{{%media}}', [
            'id' => $this->primaryKey()->unsigned(),
            'path' => $this->string(255)->notNull()->unique(),
            'original_name' => $this->string(255)->notNull(),
            'mime_type' => $this->string(100)->notNull(),
            'extension' => $this->string(16)->notNull(),
            'size' => $this->bigInteger()->unsigned()->notNull(),
            'alt_text' => $this->string(255),
            'created_by' => $this->integer()->unsigned(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $options);
        $this->createIndex('idx-media-mime-created', '{{%media}}', ['mime_type', 'created_at']);
        $this->addForeignKey('fk-media-user', '{{%media}}', 'created_by', '{{%user}}', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('{{%page}}', [
            'id' => $this->primaryKey()->unsigned(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(180)->notNull()->unique(),
            'summary' => $this->text(),
            'content' => $this->text(),
            'status' => $this->string(20)->notNull()->defaultValue('draft'),
            'publish_at' => $this->integer(),
            'unpublish_at' => $this->integer(),
            'featured_media_id' => $this->integer()->unsigned(),
            'seo_title' => $this->string(255),
            'seo_description' => $this->string(320),
            'seo_keywords' => $this->string(500),
            'canonical_url' => $this->string(500),
            'robots' => $this->string(50)->notNull()->defaultValue('index,follow'),
            'created_by' => $this->integer()->unsigned(),
            'updated_by' => $this->integer()->unsigned(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $options);
        $this->createIndex('idx-page-publication', '{{%page}}', ['status', 'publish_at', 'unpublish_at']);
        $this->createIndex('idx-page-featured-media', '{{%page}}', 'featured_media_id');
        $this->addForeignKey('fk-page-media', '{{%page}}', 'featured_media_id', '{{%media}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk-page-created-user', '{{%page}}', 'created_by', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk-page-updated-user', '{{%page}}', 'updated_by', '{{%user}}', 'id', 'SET NULL', 'CASCADE');

        $auth = Yii::$app->authManager;
        foreach (['managePages' => 'مدیریت صفحات پویا', 'manageMedia' => 'مدیریت رسانه‌ها'] as $name => $description) {
            if ($auth->getPermission($name) === null) {
                $permission = $auth->createPermission($name);
                $permission->description = $description;
                $auth->add($permission);
            }
            $editor = $auth->getRole('editor');
            $permission = $auth->getPermission($name);
            if ($editor !== null && !$auth->hasChild($editor, $permission)) {
                $auth->addChild($editor, $permission);
            }
        }
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        foreach (['managePages', 'manageMedia'] as $name) {
            $permission = $auth->getPermission($name);
            if ($permission !== null) {
                $auth->remove($permission);
            }
        }
        $this->dropTable('{{%page}}');
        $this->dropTable('{{%media}}');
    }
}
