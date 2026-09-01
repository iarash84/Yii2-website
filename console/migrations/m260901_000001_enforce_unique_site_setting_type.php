<?php

use yii\db\Migration;

class m260901_000001_enforce_unique_site_setting_type extends Migration
{
    public function safeUp()
    {
        $duplicates = (new \yii\db\Query())
            ->select(['type'])
            ->from('{{%site_setting}}')
            ->groupBy(['type'])
            ->having(['>', 'COUNT(*)', 1])
            ->column($this->db);

        foreach ($duplicates as $type) {
            $keepId = (new \yii\db\Query())
                ->from('{{%site_setting}}')
                ->where(['type' => $type])
                ->max('id', $this->db);
            $this->delete('{{%site_setting}}', ['and', ['type' => $type], ['<>', 'id', $keepId]]);
        }

        $this->dropIndex('idx-setting-type', '{{%site_setting}}');
        $this->createIndex('uq-site-setting-type', '{{%site_setting}}', 'type', true);
    }

    public function safeDown()
    {
        $this->dropIndex('uq-site-setting-type', '{{%site_setting}}');
        $this->createIndex('idx-setting-type', '{{%site_setting}}', 'type');
    }
}
