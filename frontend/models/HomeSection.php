<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

class HomeSection extends \yii\db\ActiveRecord
{
    use traits\TranslatableContent;

    public static function tableName() { return '{{%home_section}}'; }
    public function translatedAttributes() { return ['title', 'subtitle', 'content']; }
    public function behaviors() { return [TimestampBehavior::class]; }
    public static function typeOptions()
    {
        return [
            'content' => Yii::t('app', 'Rich content'),
            'features' => Yii::t('app', 'Features'),
            'stats' => Yii::t('app', 'Statistics'),
            'portfolio' => Yii::t('app', 'Latest portfolio items'),
            'posts' => Yii::t('app', 'Latest posts'),
            'faqs' => Yii::t('app', 'Frequently asked questions'),
            'cta' => Yii::t('app', 'Call to action'),
        ];
    }
    public function rules()
    {
        return [
            [['type', 'title'], 'required'], [['content'], 'string'],
            [['sort_order', 'status', 'created_by', 'updated_by'], 'integer'],
            [['sort_order'], 'default', 'value' => 0], [['status'], 'default', 'value' => 1],
            [['type'], 'in', 'range' => array_keys(self::typeOptions())],
            [['title'], 'string', 'max' => 255], [['subtitle'], 'string', 'max' => 500],
        ];
    }
    public function attributeLabels()
    {
        return ['type'=>Yii::t('app','Section type'),'title'=>Yii::t('app','Title'),'subtitle'=>Yii::t('app','Subtitle'),'content'=>Yii::t('app','Content'),'sort_order'=>Yii::t('app','Display order'),'status'=>Yii::t('app','Published')];
    }
}
