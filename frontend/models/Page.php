<?php

namespace frontend\models;

use frontend\models\traits\TranslatableContent;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Page extends ActiveRecord
{
    use TranslatableContent {
        saveTranslations as private saveContentTranslations;
    }

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_SCHEDULED = 'scheduled';

    public static function tableName()
    {
        return '{{%page}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function translatedAttributes()
    {
        return ['title', 'slug', 'summary', 'content', 'seo_title', 'seo_description', 'seo_keywords'];
    }

    public function rules()
    {
        return [
            [['title', 'slug', 'status'], 'required'],
            [['summary', 'content'], 'string'],
            [['publish_at', 'unpublish_at'], 'filter', 'filter' => static function ($value) {
                if ($value === null || $value === '') {
                    return null;
                }
                return is_numeric($value) ? (int) $value : strtotime($value);
            }],
            [['publish_at', 'unpublish_at', 'featured_media_id', 'created_by', 'updated_by'], 'integer'],
            [['title', 'seo_title'], 'string', 'max' => 255],
            [['slug'], 'string', 'max' => 180],
            [['slug'], 'match', 'pattern' => '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'message' => Yii::t('app', 'Use lowercase Latin letters, numbers and hyphens only.')],
            [['slug'], 'unique'],
            [['slug'], 'validateReservedSlug'],
            [['seo_description'], 'string', 'max' => 320],
            [['seo_keywords', 'canonical_url'], 'string', 'max' => 500],
            [['canonical_url'], 'url', 'defaultScheme' => 'https', 'skipOnEmpty' => true],
            [['robots'], 'in', 'range' => ['index,follow', 'noindex,follow', 'noindex,nofollow']],
            [['status'], 'in', 'range' => array_keys(self::statusOptions())],
            [['publish_at'], 'required', 'when' => static fn ($model) => $model->status === self::STATUS_SCHEDULED],
            [['unpublish_at'], 'compare', 'compareAttribute' => 'publish_at', 'operator' => '>', 'skipOnEmpty' => true],
            [['featured_media_id'], 'exist', 'targetClass' => Media::class, 'targetAttribute' => 'id', 'skipOnEmpty' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'Page title'), 'slug' => Yii::t('app', 'Slug'),
            'summary' => Yii::t('app', 'Summary'), 'content' => Yii::t('app', 'Content'),
            'status' => Yii::t('app', 'Publication status'), 'publish_at' => Yii::t('app', 'Publish at'),
            'unpublish_at' => Yii::t('app', 'Unpublish at'), 'featured_media_id' => Yii::t('app', 'Featured media'),
            'seo_title' => Yii::t('app', 'SEO title'), 'seo_description' => Yii::t('app', 'SEO description'),
            'seo_keywords' => Yii::t('app', 'SEO keywords'), 'canonical_url' => Yii::t('app', 'Canonical URL'),
            'robots' => Yii::t('app', 'Robots directive'),
        ];
    }

    public static function statusOptions()
    {
        return [self::STATUS_DRAFT => Yii::t('app', 'Draft'), self::STATUS_PUBLISHED => Yii::t('app', 'Published'), self::STATUS_SCHEDULED => Yii::t('app', 'Scheduled')];
    }

    public static function published(): ActiveQuery
    {
        $now = time();
        return self::find()->andWhere(['or',
            ['and', ['status' => self::STATUS_PUBLISHED], ['or', ['publish_at' => null], ['<=', 'publish_at', $now]]],
            ['and', ['status' => self::STATUS_SCHEDULED], ['<=', 'publish_at', $now]],
        ])->andWhere(['or', ['unpublish_at' => null], ['>', 'unpublish_at', $now]]);
    }

    public function getFeaturedMedia()
    {
        return $this->hasOne(Media::class, ['id' => 'featured_media_id']);
    }

    public function validateReservedSlug($attribute)
    {
        if (in_array($this->$attribute, self::reservedSlugs(), true)) {
            $this->addError($attribute, Yii::t('app', 'This slug is reserved by the application.'));
        }
    }

    public function saveTranslations(array $translations)
    {
        foreach ($translations as $language => $values) {
            if (!isset($values['slug']) || trim((string) $values['slug']) === '') {
                continue;
            }
            $slug = trim((string) $values['slug']);
            if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug) || in_array($slug, self::reservedSlugs(), true)) {
                $this->addError('slug', Yii::t('app', 'The translated slug is invalid or reserved.'));
                return false;
            }
            $duplicate = ContentTranslation::find()->where([
                'entity_type' => 'Page', 'language' => $language, 'attribute' => 'slug', 'value' => $slug,
            ])->andWhere(['<>', 'entity_id', $this->id])->exists();
            if ($duplicate) {
                $this->addError('slug', Yii::t('app', 'The translated slug has already been used.'));
                return false;
            }
        }
        return $this->saveContentTranslations($translations);
    }

    private static function reservedSlugs()
    {
        return ['index', 'contact', 'about', 'login', 'logout', 'faqs', 'order', 'sample', 'opportunity', 'blog', 'search', 'admin'];
    }

    public function isPublic(): bool
    {
        $now = time();
        return in_array($this->status, [self::STATUS_PUBLISHED, self::STATUS_SCHEDULED], true)
            && ($this->publish_at === null || $this->publish_at <= $now)
            && ($this->unpublish_at === null || $this->unpublish_at > $now);
    }
}
