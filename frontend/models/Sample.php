<?php

namespace frontend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "tbl_sample".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $content
 * @property string $url_link
 * @property string $url_display_name
 * @property string $image
 * @property string $createDateTime
 */
class Sample extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_sample';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            //[['title','content'],'required'],
            [['content'], 'string'],
            [['createDateTime'], 'safe'],
            [['title'], 'string', 'max' => 150],
            [['url_link', 'image'], 'string', 'max' => 255],
            [['url_display_name'], 'string', 'max' => 100]
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
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'url_link' => Yii::t('app', 'Url Link'),
            'url_display_name' => Yii::t('app', 'Url Display Name'),
            'image' => Yii::t('app', 'Image'),
            'createDateTime' => Yii::t('app', 'Create Date Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
