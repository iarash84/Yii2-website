<?php

namespace frontend\services;

use Yii;

class BackupService
{
    private const TABLES = ['user','auth_rule','auth_item','auth_item_child','auth_assignment','blog_category','blog_post','blog_tag','blog_post_tag','carousel','contact_submission','faq','login_attempt','opportunity_submission','order_submission','portfolio_item','site_setting','content_translation','menu_item','media','page','system_setting','admin_audit','visitor_daily','visitor_country_daily','visitor_page_daily','visitor_unique','home_section','dashboard_preference'];
    public static function create()
    {
        $data = ['format' => 'yii2-kamancms-backup','version' => 3,'created_at' => time(),'tables' => []];
        foreach (self::TABLES as $table) {
            if (Yii::$app->db->schema->getTableSchema($table, true)) {
                $data['tables'][$table] = Yii::$app->db->createCommand('SELECT * FROM ' . Yii::$app->db->quoteTableName($table))->queryAll();
            }
        }
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    }
    public static function restore($json)
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        $supportedFormats = ['yii2-kamancms-backup', 'yii2-website-backup'];
        if (!in_array($data['format'] ?? null, $supportedFormats, true)
            || !in_array($data['version'] ?? null, [2, 3], true)
            || !is_array($data['tables'] ?? null)) {
            throw new \RuntimeException('Invalid backup format.');
        }
        foreach (array_keys($data['tables']) as $table) {
            if (!in_array($table, self::TABLES, true)) {
                throw new \RuntimeException('Backup contains an unknown table.');
            }
        }
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            if ($db->driverName === 'mysql') {
                $db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
            }
            foreach (array_reverse(self::TABLES) as $table) {
                if (isset($data['tables'][$table]) && $db->schema->getTableSchema($table, true)) {
                    $db->createCommand()->delete($table)->execute();
                }
            }
            foreach (self::TABLES as $table) {
                $rows = $data['tables'][$table] ?? [];
                if (!$rows) {
                    continue;
                }
                $schema = $db->schema->getTableSchema($table, true);
                if (!is_array($rows) || !isset($rows[0]) || !is_array($rows[0])) {
                    throw new \RuntimeException('Backup rows are malformed.');
                }
                $columns = array_keys($rows[0]);
                foreach ($columns as $column) {
                    if (!isset($schema->columns[$column])) {
                        throw new \RuntimeException('Backup column mismatch.');
                    }
                }
                $expectedColumns = array_fill_keys($columns, true);
                $values = [];
                foreach ($rows as $row) {
                    if (!is_array($row) || count($row) !== count($columns)
                        || array_diff_key($row, $expectedColumns)
                        || array_diff_key($expectedColumns, $row)) {
                        throw new \RuntimeException('Backup row column mismatch.');
                    }
                    $values[] = array_map(static fn($column) => $row[$column], $columns);
                }
                $db->createCommand()->batchInsert($table, $columns, $values)->execute();
            }
            if ($db->driverName === 'mysql') {
                $db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();
            }
            $transaction->commit();
            Yii::$app->authManager->invalidateCache();
            Yii::$app->cache->flush();
        } catch (\Throwable $e) {
            if ($db->driverName === 'mysql') {
                $db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();
            } $transaction->rollBack();
            throw $e;
        }
    }
}
