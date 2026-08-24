<?php

namespace frontend\models;

use yii\db\ActiveRecord;

class BlogTag extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%blog_tag}}';
    }

    public function rules()
    {
        return [[['name', 'slug'], 'required'], [['name', 'slug'], 'string', 'max' => 80], [['slug'], 'unique']];
    }

    public function getPosts()
    {
        return $this->hasMany(Blog::class, ['id' => 'post_id'])->viaTable('{{%blog_post_tag}}', ['tag_id' => 'id']);
    }
}
