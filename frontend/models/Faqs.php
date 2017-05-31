<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_faqs".
 *
 * @property string $id
 * @property string $userId
 * @property string $question
 * @property string $respons
 * @property string $createDateTime
 */
class Faqs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_faqs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId'], 'integer'],
            [['respons'], 'string'],
            [['createDateTime'], 'safe'],
            [['question'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userId' => Yii::t('app', 'User ID'),
            'question' => Yii::t('app', 'Question'),
            'respons' => Yii::t('app', 'Respons'),
            'createDateTime' => Yii::t('app', 'Create Date Time'),
        ];
    }
}
