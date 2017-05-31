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
        return 'tbl_opportunity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createDateTime'], 'safe'],
            [['name', 'resume', 'email'], 'string', 'max' => 255],
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
            'resume' => Yii::t('app', 'Resume'),
            'email' => Yii::t('app', 'Email'),
            'createDateTime' => Yii::t('app', 'Create Date Time'),
        ];
    }
}
