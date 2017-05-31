<?php

namespace frontend\models;

use common\models\User;
use kartik\icons\Icon;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_setting".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property string $content
 * @property string $updateDateTime
 *
 * @property User $user
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'content'], 'string'],
            [['updateDateTime'], 'safe']
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
            'type' => Yii::t('app', 'Type'),
            'content' => Yii::t('app', 'Content'),
            'updateDateTime' => Yii::t('app', 'Update Date Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return mixed|null
     */
    public function getAbout()
    {
        $model = $this::find()->where(['type' => 'About'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    /**
     * @return mixed|null
     */
    public function getOpportunity()
    {
        $model = $this::find()->where(['type' => 'Opportunity'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    /**
     * @return mixed|null
     */
    public function getWorkingHours()
    {
        $model = $this::find()->where(['type' => 'WorkingHours'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    public function setWorkingHours($value)
    {
        $setting = new Setting();
        $setting::deleteAll(['type'=> 'WorkingHours']);
        $setting->user_id = Yii::$app->user->identity->getId();
        $setting->type = 'WorkingHours';
        $setting->content = $value;
        $setting->save();
    }

    /**
     * @return mixed|null
     */
    public function getPostalCode()
    {
        $model = $this::find()->where(['type' => 'PostalCode'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    /**
     * @param $value
     * @return bool
     */
    public function setPostalCode($value)
    {
        $setting = new Setting();
        $setting::deleteAll(['type'=> 'PostalCode']);
        $setting->user_id = Yii::$app->user->identity->getId();
        $setting->type = 'PostalCode';
        $setting->content = $value;
        return $setting->save();
    }

    /**
     * @return mixed|null
     */
    public function getFaxNumber()
    {
        $model = $this::find()->where(['type' => 'FaxNumber'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    /**
     * @param $value
     * @return bool
     */
    public function setFaxNumber($value)
    {
        $setting = new Setting();
        $setting::deleteAll(['type'=> 'FaxNumber']);
        $setting->user_id = Yii::$app->user->identity->getId();
        $setting->type = 'FaxNumber';
        $setting->content = $value;
        return $setting->save();
    }

    /**
     * @return mixed|null
     */
    public function getPhoneNumber()
    {
        $model = $this::find()->where(['type' => 'PhoneNumber'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    /**
     * @param $value
     * @return bool
     */
    public function setPhoneNumber($value)
    {
        $setting = new Setting();
        $setting::deleteAll(['type'=> 'PhoneNumber']);
        $setting->user_id = Yii::$app->user->identity->getId();
        $setting->type = 'PhoneNumber';
        $setting->content = $value;
        return $setting->save();
    }


    /**
     * @return mixed|null
     */
    public function getEmail()
    {
        $model = $this::find()->where(['type' => 'Email'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    /**
     * @param $value
     * @return bool
     */
    public function setEmail($value)
    {
        $setting = new Setting();
        $setting::deleteAll(['type'=> 'Email']);
        $setting->user_id = Yii::$app->user->identity->getId();
        $setting->type = 'Email';
        $setting->content = $value;
        return $setting->save();

    }

    /**
     * @return mixed|null
     */
    public function getAddress()
    {
        $model = $this::find()->where(['type' => 'Address'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    /**
     * @param $value
     */
    public function setAddress($value)
    {
        $setting = new Setting();
        $setting::deleteAll(['type'=> 'Address']);
        $setting->user_id = Yii::$app->user->identity->getId();
        $setting->type = 'Address';
        $setting->content = $value;
        return $setting->save();
    }


    /**
     * @return mixed|null
     */
    public function getCompanyName()
    {
        $model = $this::find()->where(['type' => 'CompanyName'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    /**
     * @param $value
     */
    public function setCompanyName($value)
    {
        $setting = new Setting();
        $setting::deleteAll(['type'=> 'CompanyName']);
        $setting->user_id = Yii::$app->user->identity->getId();
        $setting->type = 'CompanyName';
        $setting->content = $value;
        return $setting->save();
    }


    /**
     * @return mixed|null
     */
    public function getGooglePlus()
    {
        $model = $this::find()->where(['type' => 'GooglePlus'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    public function getGooglePlusLink()
    {
        $model = $this::find()->where(['type' => 'GooglePlus'])->one();

        if($model == null || empty($model->content))
            return null;
        return '<li>'.Html::a(Icon::show('googleplus', [], Icon::WHHG),$model->content,['class'=>"grey-text text-lighten-3"]).'</li>';
    }

    /**
     * @param $value
     */
    public function setGooglePlus($value)
    {
        $setting = new Setting();
        $setting::deleteAll(['type'=> 'GooglePlus']);
        $setting->user_id = Yii::$app->user->identity->getId();
        $setting->type = 'GooglePlus';
        $setting->content = $value;
        return $setting->save();
    }
    /**
     * @return mixed|null
     */
    public function getTwitter()
    {
        $model = $this::find()->where(['type' => 'Twitter'])->one();

        if($model == null)
            return null;
        return $model->content;
    }


    public function getTwitterLink()
    {
        $model = $this::find()->where(['type' => 'Twitter'])->one();

        if($model == null || empty($model->content))
            return null;
        return '<li>'.Html::a(Icon::show('twitter', [], Icon::WHHG),$model->content,['class'=>"grey-text text-lighten-3"]).'</li>';
    }

    /**
     * @param $value
     */
    public function setTwitter($value)
    {
        $setting = new Setting();
        $setting::deleteAll(['type'=> 'Twitter']);
        $setting->user_id = Yii::$app->user->identity->getId();
        $setting->type = 'Twitter';
        $setting->content = $value;
        return $setting->save();
    }
    /**
     * @return mixed|null
     */
    public function getLinkedin()
    {
        $model = $this::find()->where(['type' => 'Linkedin'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    public function getLinkedinLink()
    {
        $model = $this::find()->where(['type' => 'Linkedin'])->one();

        if($model == null || empty($model->content))
            return null;
        return '<li>'.Html::a(Icon::show('linkedin', [], Icon::WHHG),$model->content,['class'=>"grey-text text-lighten-3"]).'</li>';
    }

    /**
     * @param $value
     */
    public function setLinkedin($value)
    {
        $setting = new Setting();
        $setting::deleteAll(['type'=> 'Linkedin']);
        $setting->user_id = Yii::$app->user->identity->getId();
        $setting->type = 'Linkedin';
        $setting->content = $value;
        return $setting->save();
    }
    /**
     * @return mixed|null
     */
    public function getAparat()
    {
        $model = $this::find()->where(['type' => 'Aparat'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    public function getAparatLink()
    {
        $model = $this::find()->where(['type' => 'Aparat'])->one();

        if($model == null || empty($model->content))
            return null;
        return '<li>'.Html::a(Icon::show('moviereelalt', [], Icon::WHHG),$model->content,['class'=>"grey-text text-lighten-3"]).'</li>';
    }

    /**
     * @param $value
     */
    public function setAparat($value)
    {
        $setting = new Setting();
        $setting::deleteAll(['type'=> 'Aparat']);
        $setting->user_id = Yii::$app->user->identity->getId();
        $setting->type = 'Aparat';
        $setting->content = $value;
        return $setting->save();
    }
    /**
     * @return mixed|null
     */
    public function getTelegram()
    {
        $model = $this::find()->where(['type' => 'Telegram'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    public function getTelegramLink()
    {
        $model = $this::find()->where(['type' => 'Telegram'])->one();

        if($model == null || empty($model->content))
            return null;
        return '<li>'.Html::a(Icon::show('yui', [], Icon::WHHG),$model->content,['class'=>"grey-text text-lighten-3"]).'</li>';
    }

    /**
     * @param $value
     */
    public function setTelegram($value)
    {
        $setting = new Setting();
        $setting::deleteAll(['type'=> 'Telegram']);
        $setting->user_id = Yii::$app->user->identity->getId();
        $setting->type = 'Telegram';
        $setting->content = $value;
        return $setting->save();
    }
    /**
     * @return mixed|null
     */
    public function getYoutube()
    {
        $model = $this::find()->where(['type' => 'Youtube'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    public function getYoutubeLink()
    {
        $model = $this::find()->where(['type' => 'Youtube'])->one();

        if($model == null || empty($model->content))
            return null;
        return '<li>'.Html::a(Icon::show('youtube', [], Icon::WHHG),$model->content,['class'=>"grey-text text-lighten-3"]).'</li>';
    }

    /**
     * @param $value
     */
    public function setYoutube($value)
    {
        $setting = new Setting();
        $setting::deleteAll(['type'=> 'Youtube']);
        $setting->user_id = Yii::$app->user->identity->getId();
        $setting->type = 'Youtube';
        $setting->content = $value;
        return $setting->save();
    }
    /**
     * @return mixed|null
     */
    public function getInstagram()
    {
        $model = $this::find()->where(['type' => 'Instagram'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    public function getInstagramLink()
    {
        $model = $this::find()->where(['type' => 'Instagram'])->one();

        if($model == null || empty($model->content))
            return null;
        return '<li>'.Html::a(Icon::show('instagramtwo', [], Icon::WHHG),$model->content,['class'=>"grey-text text-lighten-3"]).'</li>';
    }


    /**
     * @param $value
     */
    public function setInstagram($value)
    {
        $setting = new Setting();
        $setting::deleteAll(['type'=> 'Instagram']);
        $setting->user_id = Yii::$app->user->identity->getId();
        $setting->type = 'Instagram';
        $setting->content = $value;
        return $setting->save();
    }

    /**
     * @return mixed|null
     */
    public function getFacebook()
    {
        $model = $this::find()->where(['type' => 'Facebook'])->one();

        if($model == null)
            return null;
        return $model->content;
    }

    /**
     * @return mixed|null
     */
    public function getFacebookLink()
    {
        $model = $this::find()->where(['type' => 'Facebook'])->one();

        if($model == null || empty($model->content))
            return null;
        return '<li>'.Html::a(Icon::show('facebook', [], Icon::WHHG),$model->content,['class'=>"grey-text text-lighten-3"]).'</li>';
    }

    /**
     * @param $value
     */
    public function setFacebook($value)
    {
        $setting = new Setting();
        $setting::deleteAll(['type'=> 'Facebook']);
        $setting->user_id = Yii::$app->user->identity->getId();
        $setting->type = 'Facebook';
        $setting->content = $value;
        return $setting->save();
    }

}
