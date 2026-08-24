<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Media extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%media}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            [['path', 'original_name', 'mime_type', 'extension', 'size'], 'required'],
            [['size', 'created_by'], 'integer'],
            [['path', 'original_name', 'alt_text'], 'string', 'max' => 255],
            [['mime_type'], 'string', 'max' => 100],
            [['extension'], 'string', 'max' => 16],
            [['path'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return ['alt_text' => Yii::t('app', 'Alternative text'), 'original_name' => Yii::t('app', 'File name'), 'mime_type' => Yii::t('app', 'File type'), 'size' => Yii::t('app', 'File size')];
    }

    public function getUrl()
    {
        return Yii::getAlias('@web/' . ltrim($this->path, '/'));
    }

    public function getIsImage()
    {
        return strpos($this->mime_type, 'image/') === 0;
    }
}
