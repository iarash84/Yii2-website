<?php

use yii\db\Migration;

class m260823_000002_remove_logged_passwords extends Migration
{
    public function safeUp()
    {
        $this->update('tbl_log', ['password' => null]);
        $this->alterColumn('tbl_log', 'ip', $this->string(45));
    }

    public function safeDown()
    {
        $this->alterColumn('tbl_log', 'ip', $this->string(20));
    }
}
