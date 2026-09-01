<?php

use yii\db\Migration;

class m260831_000002_add_carousel_content_controls extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%carousel}}', 'show_content', $this->boolean()->notNull()->defaultValue(false)->after('text'));
        $this->addColumn('{{%carousel}}', 'eyebrow', $this->string(128)->notNull()->defaultValue('')->after('show_content'));
        $this->addColumn('{{%carousel}}', 'primary_button_label', $this->string(128)->notNull()->defaultValue('')->after('link'));
        $this->addColumn('{{%carousel}}', 'secondary_link', $this->string(255)->notNull()->defaultValue('')->after('primary_button_label'));
        $this->addColumn('{{%carousel}}', 'secondary_button_label', $this->string(128)->notNull()->defaultValue('')->after('secondary_link'));

        $firstId = $this->db->createCommand(
            'SELECT [[id]] FROM {{%carousel}} WHERE [[status]] = 1 ORDER BY [[sort_order]], [[id]] LIMIT 1'
        )->queryScalar();
        if ($firstId !== false) {
            $this->update('{{%carousel}}', ['show_content' => 1], ['id' => $firstId]);
        }
    }

    public function safeDown()
    {
        $this->dropColumn('{{%carousel}}', 'secondary_button_label');
        $this->dropColumn('{{%carousel}}', 'secondary_link');
        $this->dropColumn('{{%carousel}}', 'primary_button_label');
        $this->dropColumn('{{%carousel}}', 'eyebrow');
        $this->dropColumn('{{%carousel}}', 'show_content');
    }
}
