<?php

namespace frontend\services;

use Yii;

class BackupService
{
    private const TABLES = ['user','auth_rule','auth_item','auth_item_child','auth_assignment','blog_category','blog_post','blog_tag','blog_post_tag','carousel','contact_submission','faq','login_attempt','opportunity_submission','order_submission','portfolio_item','site_setting','content_translation','menu_item','media','page','system_setting','admin_audit','visitor_daily','visitor_country_daily','visitor_page_daily'];
    public static function create()
    {
        $data = ['format' => 'yii2-website-backup','version' => 2,'created_at' => time(),'tables' => []];
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
        if (($data['format'] ?? null) !== 'yii2-website-backup' || ($data['version'] ?? null) !== 2 || !is_array($data['tables'] ?? null)) {
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
                $columns = array_keys($rows[0]);
                foreach ($columns as $column) {
                    if (!isset($schema->columns[$column])) {
                        throw new \RuntimeException('Backup column mismatch.');
                    }
                }
                $values = array_map(static fn($row) => array_values(array_intersect_key($row, array_flip($columns))), $rows);
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
