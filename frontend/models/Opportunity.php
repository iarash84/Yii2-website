<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_opportunity".
 *
 * @property string $id
 * @property string $name
 * @property string $phoneNumber
 * @property string $resume
 * @property string $email
 * @property string $createDateTime
 */
class Opportunity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%opportunity_submission}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['name', 'resume', 'email'], 'string', 'max' => 255],
            [['phone_number'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name and family'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'resume' => Yii::t('app', 'Resume'),
            'email' => Yii::t('app', 'Email'),
            'created_at' => Yii::t('app', 'Create Date Time'),
        ];
    }
}
