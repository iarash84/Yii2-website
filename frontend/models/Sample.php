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
    use \frontend\models\traits\TranslatableContent;

    public function translatedAttributes()
    {
        return ['title', 'content', 'link_label'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%portfolio_item}}';
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
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 150],
            [['link_url', 'image'], 'string', 'max' => 255],
            [['link_label'], 'string', 'max' => 100]
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
            'link_url' => Yii::t('app', 'Url Link'),
            'link_label' => Yii::t('app', 'Url Display Name'),
            'image' => Yii::t('app', 'Image'),
            'created_at' => Yii::t('app', 'Create Date Time'),
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
