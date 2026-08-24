<?php

namespace frontend\models;

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
    use \frontend\models\traits\TranslatableContent;

    public $hashtags;

    public function translatedAttributes()
    {
        return ['title', 'description', 'content', 'keyWord'];
    }
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
            [['title', 'keyWord'], 'string', 'max' => 255],
            [['hashtags'], 'string', 'max' => 500],
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
            'hashtags' => Yii::t('app', 'Hashtags'),
            'createDatetime' => Yii::t('app', 'Create Datetime'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getTags()
    {
        return $this->hasMany(BlogTag::class, ['id' => 'tag_id'])
            ->viaTable('{{%blog_post_tag}}', ['post_id' => 'id']);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->hashtags = implode(', ', array_map(static function ($tag) {
            return '#' . $tag->name;
        }, $this->tags));
    }

    public function syncTags($input)
    {
        $names = preg_split('/[,،\s]+/u', (string) $input, -1, PREG_SPLIT_NO_EMPTY);
        $names = array_slice(array_values(array_unique(array_filter(array_map(static function ($name) {
            return mb_substr(ltrim(trim($name), '#'), 0, 80);
        }, $names)))), 0, 20);

        $transaction = static::getDb()->beginTransaction();
        try {
            static::getDb()->createCommand()->delete('{{%blog_post_tag}}', ['post_id' => $this->id])->execute();
            foreach ($names as $name) {
                $slug = trim(preg_replace('/[^\pL\pN]+/u', '-', mb_strtolower($name)), '-');
                $slug = $slug !== '' ? $slug : sha1($name);
                $tag = BlogTag::findOne(['slug' => $slug]) ?: new BlogTag(['name' => $name, 'slug' => $slug]);
                if (!$tag->save()) {
                    throw new \RuntimeException(implode(' ', $tag->getFirstErrors()));
                }
                static::getDb()->createCommand()->insert('{{%blog_post_tag}}', ['post_id' => $this->id, 'tag_id' => $tag->id])->execute();
            }
            $transaction->commit();
            return true;
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}
