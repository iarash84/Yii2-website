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
        return 'tbl_contact_us';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['body'], 'string'],
            [['createDateTime'], 'safe'],
            [['name', 'email', 'subject'], 'string', 'max' => 255],
            [['phoneNumber'], 'string', 'max' => 20]
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
            'phoneNumber' => Yii::t('app', 'Phone Number'),
            'email' => Yii::t('app', 'Email'),
            'subject' => Yii::t('app', 'Subject'),
            'body' => Yii::t('app', 'Body'),
            'createDateTime' => Yii::t('app', 'Create Date Time'),
        ];
    }
}
