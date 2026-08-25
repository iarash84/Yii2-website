<?php

namespace frontend\models;

use frontend\models\traits\TranslatableContent;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;

class MenuItem extends ActiveRecord
{
    use TranslatableContent;

    public static function tableName()
    {
        return '{{%menu_item}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function translatedAttributes()
    {
        return ['label'];
    }

    public function rules()
    {
        return [
            [['label', 'url', 'location', 'target'], 'required'],
            [['parent_id', 'sort_order', 'status', 'created_by'], 'integer'],
            [['sort_order'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 1],
            [['label'], 'string', 'max' => 120],
            [['url'], 'string', 'max' => 500],
            [['location'], 'in', 'range' => ['main', 'footer']],
            [['target'], 'in', 'range' => ['_self', '_blank']],
            [['url'], 'validateUrl'],
            [['parent_id'], 'exist', 'targetClass' => self::class, 'targetAttribute' => 'id', 'skipOnEmpty' => true],
        ];
    }

    public function validateUrl($attribute)
    {
        $value = trim((string) $this->$attribute);
        if (strpos($value, '/') === 0 || strpos($value, '#') === 0) {
            return;
        }
        if (!filter_var($value, FILTER_VALIDATE_URL) || !in_array(parse_url($value, PHP_URL_SCHEME), ['http', 'https'], true)) {
            $this->addError($attribute, Yii::t('app', 'Enter an internal path or a valid HTTP(S) URL.'));
        }
    }

    public function attributeLabels()
    {
        return [
            'label' => Yii::t('app', 'Menu label'),
            'url' => Yii::t('app', 'Menu URL'),
            'location' => Yii::t('app', 'Menu location'),
            'target' => Yii::t('app', 'Link target'),
            'parent_id' => Yii::t('app', 'Parent item'),
            'sort_order' => Yii::t('app', 'Display order'),
            'status' => Yii::t('app', 'Published'),
        ];
    }

    public function getChildren()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id'])->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC]);
    }

    public static function activeRoots($location = 'main')
    {
        return self::find()->with(['children' => function ($query) {
            $query->andWhere(['status' => 1]);
        }])->where(['location' => $location, 'status' => 1, 'parent_id' => null])
            ->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])->all();
    }

    public function getPublicUrl()
    {
        if (strpos($this->url, '/') !== 0 || strpos($this->url, '//') === 0) {
            return $this->url;
        }

        $language = Yii::$app->languageManager->activeLanguage;
        $path = '/' . ltrim($this->url, '/');
        if ($path === '/' || strpos($path, '/admin') === 0 || strpos($path, '/' . $language . '/') === 0) {
            return Url::to($path);
        }

        return Url::to('/' . $language . ($path === '/index' ? '' : $path));
    }
}
