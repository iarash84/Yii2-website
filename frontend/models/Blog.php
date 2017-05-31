<?php

namespace frontend\models;

use app\models\BlogCategory;
use common\models\User;
use Yii;

/**
 * This is the model class for table "tbl_blog_post".
 *
 * @property string $id
 * @property string $user_id
 * @property string $category_id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $keyWord
 * @property string $createDatetime
 *
 * @property TblBlogCategory $category
 * @property User $user
 */
class Blog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_blog_post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','category_id'],'required'],
            [['user_id', 'category_id'], 'integer'],
            [['description', 'content'], 'string'],
            [['createDatetime'], 'safe'],
            [['title', 'keyWord'], 'string', 'max' => 255]
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
            'category_id' => Yii::t('app', 'Category'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Summery'),
            'content' => Yii::t('app', 'Main Content'),
            'keyWord' => Yii::t('app', 'Key Word'),
            'createDatetime' => Yii::t('app', 'Create Datetime'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(BlogCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
