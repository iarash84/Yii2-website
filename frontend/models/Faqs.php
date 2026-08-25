<?php

namespace frontend\models;

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
    use \frontend\models\traits\TranslatableContent;
    public function translatedAttributes() { return ['question', 'answer']; }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%faq}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'answer'], 'required'],
            [['user_id', 'status', 'sort_order'], 'integer'],
            [['sort_order'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 1],
            [['answer'], 'string'],
            [['created_at'], 'safe'],
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
            'user_id' => Yii::t('app', 'User ID'),
            'question' => Yii::t('app', 'Question'),
            'answer' => Yii::t('app', 'Respons'),
            'created_at' => Yii::t('app', 'Create Date Time'),
            'status' => Yii::t('app', 'Published'),
            'sort_order' => Yii::t('app', 'Display order'),
        ];
    }
}
