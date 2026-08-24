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
        return 'tbl_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['success'], 'integer'],
            [['userAgent'], 'string'],
            [['createDateTime'], 'safe'],
            [['user'], 'string', 'max' => 50],
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
            'user' => Yii::t('app', 'User'),
            'success' => Yii::t('app', 'Success'),
            'ip' => Yii::t('app', 'Ip'),
            'userAgent' => Yii::t('app', 'User Agent'),
            'createDateTime' => Yii::t('app', 'Create Date Time'),
        ];
    }
}
