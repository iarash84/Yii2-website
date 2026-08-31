<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_contact_us".
 *
 * @property string $id
 * @property string $name
 * @property string $phoneNumber
 * @property string $email
 * @property string $subject
 * @property string $body
 * @property string $createDateTime
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contact_submission}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['body'], 'string'],
            [['created_at', 'read_at'], 'safe'],
            [['name', 'email', 'subject'], 'string', 'max' => 255],
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
            'email' => Yii::t('app', 'Email'),
            'subject' => Yii::t('app', 'Subject'),
            'body' => Yii::t('app', 'Body'),
            'created_at' => Yii::t('app', 'Create Date Time'),
        ];
    }
}
