<?php

namespace frontend\models\traits;

use frontend\models\ContentTranslation;
use Yii;

trait TranslatableContent
{
    private $translationCache = [];

    abstract public function translatedAttributes();

    public function getLocalized($attribute, $language = null)
    {
        if (!in_array($attribute, $this->translatedAttributes(), true)) {
            throw new \InvalidArgumentException("Attribute {$attribute} is not translatable.");
        }

        $manager = Yii::$app->languageManager;
        foreach ($manager->getFallbacks($language) as $code) {
            if ($code === $manager->defaultLanguage) {
                $value = $this->getAttribute($attribute);
            } else {
                $value = $this->getTranslationValue($attribute, $code);
            }
            if ($value !== null && $value !== '') {
                return $value;
            }
        }
        return $this->getAttribute($attribute);
    }

    public function getTranslationValue($attribute, $language)
    {
        if ($this->getIsNewRecord()) {
            return null;
        }
        $key = $language . ':' . $attribute;
        if (!array_key_exists($key, $this->translationCache)) {
            $this->translationCache[$key] = ContentTranslation::find()
                ->select('value')
                ->where($this->translationIdentity($attribute, $language))
                ->scalar();
        }
        return $this->translationCache[$key] === false ? null : $this->translationCache[$key];
    }

    public function saveTranslations(array $translations)
    {
        if ($this->getIsNewRecord()) {
            return false;
        }
        $manager = Yii::$app->languageManager;
        foreach ($translations as $language => $values) {
            $language = $manager->normalize($language);
            if ($language === null || $language === $manager->defaultLanguage || !is_array($values)) {
                continue;
            }
            foreach ($this->translatedAttributes() as $attribute) {
                if (!array_key_exists($attribute, $values)) {
                    continue;
                }
                $identity = $this->translationIdentity($attribute, $language);
                $value = trim((string) $values[$attribute]);
                if ($value === '') {
                    ContentTranslation::deleteAll($identity);
                    continue;
                }
                $model = ContentTranslation::findOne($identity) ?: new ContentTranslation($identity);
                $model->value = $value;
                $model->updated_at = time();
                if (!$model->save()) {
                    return false;
                }
                $this->translationCache[$language . ':' . $attribute] = $value;
            }
        }
        return true;
    }

    public function delete()
    {
        $result = parent::delete();
        if ($result !== false) {
            ContentTranslation::deleteAll([
                'entity_type' => $this->translationEntityType(),
                'entity_id' => $this->getPrimaryKey(),
            ]);
        }
        return $result;
    }

    private function translationIdentity($attribute, $language)
    {
        return [
            'entity_type' => $this->translationEntityType(),
            'entity_id' => $this->getPrimaryKey(),
            'language' => $language,
            'attribute' => $attribute,
        ];
    }

    private function translationEntityType()
    {
        return str_replace('frontend\\models\\', '', static::class);
    }
}
