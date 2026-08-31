<?php

use yii\db\Migration;

class m260831_000001_add_submission_read_state extends Migration
{
    private const TABLES = ['contact_submission', 'order_submission', 'opportunity_submission'];

    public function safeUp()
    {
        foreach (self::TABLES as $table) {
            $this->addColumn('{{%' . $table . '}}', 'read_at', $this->integer()->null());
            $this->update('{{%' . $table . '}}', ['read_at' => time()]);
            $this->createIndex('idx-' . $table . '-read-created', '{{%' . $table . '}}', ['read_at', 'created_at']);
        }
    }

    public function safeDown()
    {
        foreach (array_reverse(self::TABLES) as $table) {
            $this->dropIndex('idx-' . $table . '-read-created', '{{%' . $table . '}}');
            $this->dropColumn('{{%' . $table . '}}', 'read_at');
        }
    }
}
