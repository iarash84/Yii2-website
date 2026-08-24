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
            [['facebook', 'twitter', 'linkedin', 'aparat', 'telegram', 'youtube', 'instagram'], 'url', 'validSchemes' => ['http', 'https']],
            [['facebook', 'twitter', 'linkedin', 'aparat', 'telegram', 'youtube', 'instagram'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'facebook' => Yii::t('app', 'Facebook'),
            'twitter' => Yii::t('app', 'Twitter'),
            'linkedin' => Yii::t('app', 'Linkedin'),
            'aparat' => Yii::t('app', 'Aparat'),
            'instagram' => Yii::t('app', 'Instagram'),
            'youtube' => Yii::t('app', 'Youtube'),
            'telegram' => Yii::t('app', 'Telegram')
        ];
    }


}

