<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_log".
 *
 * @property string $id
 * @property string $user
 * @property integer $success
 * @property string $ip
 * @property string $userAgent
 * @property string $createDateTime
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%login_attempt}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['success'], 'integer'],
            [['user_agent'], 'string'],
            [['created_at'], 'safe'],
            [['username'], 'string', 'max' => 50],
            [['ip'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'User'),
            'success' => Yii::t('app', 'Success'),
            'ip' => Yii::t('app', 'Ip'),
            'user_agent' => Yii::t('app', 'User Agent'),
            'created_at' => Yii::t('app', 'Create Date Time'),
        ];
    }
}
