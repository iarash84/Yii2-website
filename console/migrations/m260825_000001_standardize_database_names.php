<?php

use yii\db\Migration;

class m260825_000001_standardize_database_names extends Migration
{
    private const TABLES = [
        'tbl_blog_category' => 'blog_category',
        'tbl_blog_post' => 'blog_post',
        'tbl_carousel' => 'carousel',
        'tbl_contact_us' => 'contact_submission',
        'tbl_faqs' => 'faq',
        'tbl_log' => 'login_attempt',
        'tbl_opportunity' => 'opportunity_submission',
        'tbl_order' => 'order_submission',
        'tbl_sample' => 'portfolio_item',
        'tbl_setting' => 'site_setting',
    ];

    private const COLUMNS = [
        'blog_category' => ['createDatetime' => 'created_at'],
        'blog_post' => ['keyWord' => 'keywords', 'createDatetime' => 'created_at'],
        'carousel' => ['order_num' => 'sort_order'],
        'contact_submission' => ['phoneNumber' => 'phone_number', 'createDateTime' => 'created_at'],
        'faq' => ['userId' => 'user_id', 'respons' => 'answer', 'createDateTime' => 'created_at'],
        'login_attempt' => ['user' => 'username', 'userAgent' => 'user_agent', 'createDateTime' => 'created_at'],
        'opportunity_submission' => ['phoneNumber' => 'phone_number', 'createDateTime' => 'created_at'],
        'order_submission' => ['phoneNumber' => 'phone_number', 'createDateTime' => 'created_at'],
        'portfolio_item' => ['url_link' => 'link_url', 'url_display_name' => 'link_label', 'createDateTime' => 'created_at'],
        'site_setting' => ['updateDateTime' => 'updated_at'],
    ];

    public function safeUp()
    {
        foreach (self::TABLES as $old => $new) {
            $this->renameTable('{{%' . $old . '}}', '{{%' . $new . '}}');
        }
        foreach (self::COLUMNS as $table => $columns) {
            foreach ($columns as $old => $new) {
                $this->renameColumn('{{%' . $table . '}}', $old, $new);
            }
        }
    }

    public function safeDown()
    {
        foreach (array_reverse(self::COLUMNS, true) as $table => $columns) {
            foreach (array_reverse($columns, true) as $old => $new) {
                $this->renameColumn('{{%' . $table . '}}', $new, $old);
            }
        }
        foreach (array_reverse(self::TABLES, true) as $old => $new) {
            $this->renameTable('{{%' . $new . '}}', '{{%' . $old . '}}');
        }
    }
}
