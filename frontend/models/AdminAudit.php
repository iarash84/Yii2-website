<?php

namespace frontend\models;

use yii\db\ActiveRecord;

class AdminAudit extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%admin_audit}}';
    }
    public function rules()
    {
        return [[['route', 'action', 'method', 'created_at'], 'required'], [['user_id', 'created_at'], 'integer'], [['details'], 'string'], [['route'], 'string', 'max' => 180], [['action'], 'string', 'max' => 80], [['method'], 'string', 'max' => 10], [['ip'], 'string', 'max' => 45], [['user_agent'], 'string', 'max' => 500]];
    }
    public function getUser()
    {
        return $this->hasOne(\common\models\User::class, ['id' => 'user_id']);
    }
}
