<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_order".
 *
 * @property string $id
 * @property string $name
 * @property string $company
 * @property string $phoneNumber
 * @property string $website
 * @property string $email
 * @property string $description
 * @property string $createDateTime
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['createDateTime'], 'safe'],
            [['name', 'company', 'website', 'email'], 'string', 'max' => 255],
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
            'company' => Yii::t('app', 'Company'),
            'phoneNumber' => Yii::t('app', 'Phone Number'),
            'website' => Yii::t('app', 'Website'),
            'email' => Yii::t('app', 'Email'),
            'description' => Yii::t('app', 'Description'),
            'createDateTime' => Yii::t('app', 'Create Date Time'),
        ];
    }
}
