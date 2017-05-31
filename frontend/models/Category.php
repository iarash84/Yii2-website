<?php

namespace frontend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "tbl_blog_category".
 *
 * @property string $id
 * @property string $user_id
 * @property string $title
 * @property string $createDatetime
 *
 * @property User $user
 * @property TblBlogPost[] $tblBlogPosts
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_blog_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'],'required'],
            [['user_id'], 'integer'],
            [['createDatetime'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User Created'),
            'title' => Yii::t('app', 'Title'),
            'createDatetime' => Yii::t('app', 'Create Date Time'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getTblBlogPosts()
    {
        return $this->hasMany(Blog::className(), ['category_id' => 'id']);
    }
}
