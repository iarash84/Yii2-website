<?php

use yii\db\Migration;

class m260824_000002_create_content_translation extends Migration
{
    public function safeUp()
    {
        $options = $this->db->driverName === 'mysql'
            ? 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB'
            : null;

        $this->createTable('{{%content_translation}}', [
            'id' => $this->primaryKey()->unsigned(),
            'entity_type' => $this->string(64)->notNull(),
            'entity_id' => $this->integer()->unsigned()->notNull(),
            'language' => $this->string(12)->notNull(),
            'attribute' => $this->string(64)->notNull(),
            'value' => $this->text(),
            'updated_at' => $this->integer()->notNull(),
        ], $options);
        $this->createIndex(
            'uq-content-translation-entity-language-attribute',
            '{{%content_translation}}',
            ['entity_type', 'entity_id', 'language', 'attribute'],
            true
        );
        $this->createIndex('idx-content-translation-language', '{{%content_translation}}', 'language');
    }

    public function safeDown()
    {
        $this->dropTable('{{%content_translation}}');
    }
}
