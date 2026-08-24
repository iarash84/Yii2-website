<?php

use yii\db\Migration;

class m260824_000001_finalize_install_schema extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('tbl_setting', 'type', $this->string(32));
        if ($this->db->schema->getTableSchema('tbl_log', true)->getColumn('password') !== null) {
            $this->dropColumn('tbl_log', 'password');
        }
        if ($this->db->driverName === 'mysql') {
            $this->execute('ALTER TABLE {{%migration}} CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        }
    }

    public function safeDown()
    {
        $this->addColumn('tbl_log', 'password', $this->string(100));
    }
}
