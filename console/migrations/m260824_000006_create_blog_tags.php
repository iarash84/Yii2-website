<?php

use yii\db\Migration;

class m260824_000006_create_blog_tags extends Migration
{
    public function safeUp()
    {
        $options = $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB' : null;
        $this->createTable('{{%blog_tag}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(80)->notNull(),
            'slug' => $this->string(80)->notNull()->unique(),
        ], $options);
        $this->createTable('{{%blog_post_tag}}', [
            'post_id' => $this->integer()->unsigned()->notNull(),
            'tag_id' => $this->integer()->unsigned()->notNull(),
            'PRIMARY KEY ([[post_id]], [[tag_id]])',
        ], $options);
        $this->createIndex('idx-blog-post-tag-tag', '{{%blog_post_tag}}', 'tag_id');
        $this->addForeignKey('fk-blog-post-tag-post', '{{%blog_post_tag}}', 'post_id', '{{%tbl_blog_post}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-blog-post-tag-tag', '{{%blog_post_tag}}', 'tag_id', '{{%blog_tag}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%blog_post_tag}}');
        $this->dropTable('{{%blog_tag}}');
    }
}
