<?php

namespace frontend\models;

class DashboardPreference extends \yii\db\ActiveRecord
{
    public const WIDGETS = ['metrics', 'analytics', 'quick_actions', 'recent_activity', 'system_status'];
    public static function tableName() { return '{{%dashboard_preference}}'; }
    public function rules() { return [[['user_id', 'layout_json', 'updated_at'], 'required'], [['user_id', 'updated_at'], 'integer'], [['layout_json'], 'string']]; }
    public static function layoutFor($userId)
    {
        $model = self::findOne($userId);
        $saved = $model ? json_decode($model->layout_json, true) : null;
        return self::normalize(is_array($saved) ? $saved : []);
    }
    public static function normalize(array $layout)
    {
        $order = array_values(array_unique(array_intersect($layout['order'] ?? [], self::WIDGETS)));
        foreach (self::WIDGETS as $widget) if (!in_array($widget, $order, true)) $order[] = $widget;
        return ['order' => $order, 'hidden' => array_values(array_unique(array_intersect($layout['hidden'] ?? [], self::WIDGETS)))];
    }
}
