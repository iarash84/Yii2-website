<?php
namespace frontend\models;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SocialForm extends Model
{
    public $facebook;
    public $googlePlus;
    public $twitter;
    public $linkedin;
    public $aparat;
    public $telegram;
    public $instagram;
    public $youtube;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['facebook', 'googlePlus', 'twitter', 'linkedin', 'aparat', 'telegram' , 'youtube', 'instagram'], 'string'],
            [['facebook', 'googlePlus', 'twitter', 'linkedin', 'aparat', 'telegram' , 'youtube', 'instagram'], 'safe'],
            [['facebook', 'googlePlus', 'twitter', 'linkedin', 'aparat', 'telegram' , 'youtube', 'instagram'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'facebook' => Yii::t('app', 'Facebook'),
            'googlePlus' => Yii::t('app', 'GooglePlus'),
            'twitter' => Yii::t('app', 'Twitter'),
            'linkedin' => Yii::t('app', 'Linkedin'),
            'aparat' => Yii::t('app', 'Aparat'),
            'instagram' => Yii::t('app', 'Instagram'),
            'youtube' => Yii::t('app', 'Youtube'),
            'telegram' => Yii::t('app', 'Telegram')
        ];
    }


}

