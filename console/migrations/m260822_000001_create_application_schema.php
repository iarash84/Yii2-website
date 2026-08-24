<?php

use yii\db\Migration;

class m260822_000001_create_application_schema extends Migration
{
    private $tableOptions;

    public function safeUp()
    {
        $this->tableOptions = $this->db->driverName === 'mysql'
            ? 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB'
            : null;

        $this->createRbacTables();
        $this->createContentTables();
        $this->dropLegacyRbacForeignKeys();
        $this->convertExistingTablesToUtf8mb4();
        $this->makeLegacyForeignKeyColumnsNullable();
        $this->createIndexesAndForeignKeys();
    }

    public function safeDown()
    {
        foreach ([
            'tbl_setting', 'tbl_sample', 'tbl_order', 'tbl_opportunity', 'tbl_log',
            'tbl_faqs', 'tbl_contact_us', 'tbl_carousel', 'tbl_blog_post',
            'tbl_blog_category', 'auth_assignment', 'auth_item_child', 'auth_item', 'auth_rule',
        ] as $table) {
            if ($this->db->schema->getTableSchema($table, true) !== null) {
                $this->dropTable($table);
            }
        }
    }

    private function createRbacTables()
    {
        $this->createIfMissing('auth_rule', [
            'name' => $this->string(64)->notNull(),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY ([[name]])',
        ]);
        $this->createIfMissing('auth_item', [
            'name' => $this->string(64)->notNull(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY ([[name]])',
        ]);
        $this->createIfMissing('auth_item_child', [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
            'PRIMARY KEY ([[parent]], [[child]])',
        ]);
        $this->createIfMissing('auth_assignment', [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY ([[item_name]], [[user_id]])',
        ]);
    }

    private function createContentTables()
    {
        $this->createIfMissing('tbl_blog_category', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned(),
            'title' => $this->string(255),
            'createDatetime' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIfMissing('tbl_blog_post', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned(),
            'category_id' => $this->integer()->unsigned(),
            'title' => $this->string(255),
            'description' => $this->text(),
            'content' => $this->text(),
            'keyWord' => $this->string(255),
            'createDatetime' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIfMissing('tbl_carousel', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned(),
            'image' => $this->string(255)->notNull(),
            'link' => $this->string(255)->notNull()->defaultValue(''),
            'title' => $this->string(128),
            'text' => $this->text(),
            'order_num' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
        ]);
        $this->createIfMissing('tbl_contact_us', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255),
            'phoneNumber' => $this->string(20),
            'email' => $this->string(255),
            'subject' => $this->string(255),
            'body' => $this->text(),
            'createDateTime' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIfMissing('tbl_faqs', [
            'id' => $this->primaryKey()->unsigned(),
            'userId' => $this->integer()->unsigned(),
            'question' => $this->string(150),
            'respons' => $this->text(),
            'createDateTime' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIfMissing('tbl_log', [
            'id' => $this->primaryKey()->unsigned(),
            'user' => $this->string(50),
            'password' => $this->string(100),
            'success' => $this->smallInteger(),
            'ip' => $this->string(45),
            'userAgent' => $this->text(),
            'createDateTime' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIfMissing('tbl_opportunity', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255),
            'phoneNumber' => $this->string(20),
            'resume' => $this->string(255),
            'email' => $this->string(255),
            'createDateTime' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIfMissing('tbl_order', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255),
            'company' => $this->string(255),
            'phoneNumber' => $this->string(20),
            'website' => $this->string(255),
            'email' => $this->string(255),
            'description' => $this->text(),
            'createDateTime' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIfMissing('tbl_sample', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned(),
            'title' => $this->string(150),
            'content' => $this->text(),
            'url_link' => $this->string(255),
            'url_display_name' => $this->string(100),
            'image' => $this->string(255),
            'createDateTime' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIfMissing('tbl_setting', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned(),
            'type' => $this->string(32),
            'content' => $this->text(),
            'updateDateTime' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    private function createIndexesAndForeignKeys()
    {
        $indexes = [
            ['idx-auth-item-type', 'auth_item', 'type'],
            ['idx-auth-item-rule-name', 'auth_item', 'rule_name'],
            ['idx-auth-item-child-child', 'auth_item_child', 'child'],
            ['idx-auth-assignment-user-id', 'auth_assignment', 'user_id'],
            ['idx-blog-category-user-id', 'tbl_blog_category', 'user_id'],
            ['idx-blog-post-user-id', 'tbl_blog_post', 'user_id'],
            ['idx-blog-post-category-id', 'tbl_blog_post', 'category_id'],
            ['idx-carousel-user-id', 'tbl_carousel', 'user_id'],
            ['idx-carousel-status-order', 'tbl_carousel', ['status', 'order_num']],
            ['idx-contact-created-at', 'tbl_contact_us', 'createDateTime'],
            ['idx-faq-user-id', 'tbl_faqs', 'userId'],
            ['idx-log-created-at', 'tbl_log', 'createDateTime'],
            ['idx-opportunity-created-at', 'tbl_opportunity', 'createDateTime'],
            ['idx-order-created-at', 'tbl_order', 'createDateTime'],
            ['idx-sample-user-id', 'tbl_sample', 'user_id'],
            ['idx-setting-user-id', 'tbl_setting', 'user_id'],
            ['idx-setting-type', 'tbl_setting', 'type'],
        ];
        foreach ($indexes as $index) {
            $this->createIndexIfMissing($index[0], $index[1], $index[2]);
        }

        $foreignKeys = [
            ['fk-auth-item-rule', 'auth_item', 'rule_name', 'auth_rule', 'name', 'SET NULL', 'CASCADE'],
            ['fk-auth-child-parent', 'auth_item_child', 'parent', 'auth_item', 'name', 'CASCADE', 'CASCADE'],
            ['fk-auth-child-child', 'auth_item_child', 'child', 'auth_item', 'name', 'CASCADE', 'CASCADE'],
            ['fk-auth-assignment-item', 'auth_assignment', 'item_name', 'auth_item', 'name', 'CASCADE', 'CASCADE'],
            ['fk-auth-assignment-user', 'auth_assignment', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE'],
            ['fk-blog-category-user', 'tbl_blog_category', 'user_id', 'user', 'id', 'SET NULL', 'CASCADE'],
            ['fk-blog-post-user', 'tbl_blog_post', 'user_id', 'user', 'id', 'SET NULL', 'CASCADE'],
            ['fk-blog-post-category', 'tbl_blog_post', 'category_id', 'tbl_blog_category', 'id', 'CASCADE', 'CASCADE'],
            ['fk-carousel-user', 'tbl_carousel', 'user_id', 'user', 'id', 'SET NULL', 'CASCADE'],
            ['fk-faq-user', 'tbl_faqs', 'userId', 'user', 'id', 'SET NULL', 'CASCADE'],
            ['fk-sample-user', 'tbl_sample', 'user_id', 'user', 'id', 'SET NULL', 'CASCADE'],
            ['fk-setting-user', 'tbl_setting', 'user_id', 'user', 'id', 'SET NULL', 'CASCADE'],
        ];
        foreach ($foreignKeys as $fk) {
            $this->addForeignKeyIfMissing(...$fk);
        }
    }

    private function createIfMissing($table, array $columns)
    {
        if ($this->db->schema->getTableSchema($table, true) === null) {
            $this->createTable($table, $columns, $this->tableOptions);
        }
    }

    private function createIndexIfMissing($name, $table, $columns)
    {
        $schema = $this->db->schema->getTableSchema($table, true);
        $indexes = $this->db->schema->getTableIndexes($table, true);
        if ($schema !== null && !isset($indexes[$name])) {
            $this->createIndex($name, $table, $columns);
        }
    }

    private function addForeignKeyIfMissing($name, $table, $columns, $refTable, $refColumns, $delete, $update)
    {
        $schema = $this->db->schema->getTableSchema($table, true);
        if ($schema === null) {
            return;
        }
        foreach ($schema->foreignKeys as $foreignKey) {
            if (($foreignKey[0] ?? null) === $refTable && isset($foreignKey[$columns])) {
                return;
            }
        }
        $this->addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete, $update);
    }

    private function convertExistingTablesToUtf8mb4()
    {
        if ($this->db->driverName !== 'mysql') {
            return;
        }
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        try {
            foreach ([
                'migration', 'user', 'auth_rule', 'auth_item', 'auth_item_child', 'auth_assignment',
                'tbl_blog_category', 'tbl_blog_post', 'tbl_carousel', 'tbl_contact_us',
                'tbl_faqs', 'tbl_log', 'tbl_opportunity', 'tbl_order', 'tbl_sample', 'tbl_setting',
            ] as $table) {
                if ($this->db->schema->getTableSchema($table, true) !== null) {
                    $quoted = $this->db->quoteTableName($table);
                    $this->execute("ALTER TABLE {$quoted} CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                }
            }
        } finally {
            $this->execute('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function dropLegacyRbacForeignKeys()
    {
        foreach (['auth_assignment', 'auth_item_child', 'auth_item'] as $table) {
            if ($this->db->schema->getTableSchema($table, true) === null) {
                continue;
            }
            foreach ($this->db->schema->getTableForeignKeys($table, true) as $foreignKey) {
                if ($foreignKey->name !== null) {
                    $this->dropForeignKey($foreignKey->name, $table);
                }
            }
        }
    }

    private function makeLegacyForeignKeyColumnsNullable()
    {
        $schema = $this->db->schema->getTableSchema('tbl_carousel', true);
        if ($schema !== null && isset($schema->columns['user_id']) && !$schema->columns['user_id']->allowNull) {
            $this->alterColumn('tbl_carousel', 'user_id', $this->integer()->unsigned());
        }
    }
}
