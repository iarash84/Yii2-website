<?php

namespace frontend\models;

use yii\db\ActiveRecord;

class ContentTranslation extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%content_translation}}';
    }

    public function rules()
    {
        return [
            [['entity_type', 'entity_id', 'language', 'attribute', 'updated_at'], 'required'],
            [['entity_id', 'updated_at'], 'integer'],
            [['value'], 'string'],
            [['entity_type', 'attribute'], 'string', 'max' => 64],
            [['language'], 'string', 'max' => 12],
        ];
    }
}
