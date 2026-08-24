<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\helpers\Url;

class LanguageManager extends Component
{
    public $languages = [];
    public $defaultLanguage = 'en';
    public $adminLanguage = 'en';

    private $activeLanguage;

    public function getActiveLanguage()
    {
        return $this->activeLanguage ?: $this->defaultLanguage;
    }

    public function activate($code)
    {
        $code = $this->normalize($code) ?: $this->defaultLanguage;
        $this->activeLanguage = $code;
        Yii::$app->language = $this->languages[$code]['yii'];
        return $code;
    }

    public function normalize($language)
    {
        if (isset($this->languages[$language])) {
            return $language;
        }
        foreach ($this->languages as $code => $config) {
            if (in_array($language, [$config['yii'] ?? null, $config['locale'] ?? null], true)) {
                return $code;
            }
        }
        return null;
    }

    public function isRtl($code = null)
    {
        $code = $this->normalize($code) ?: $this->getActiveLanguage();
        return ($this->languages[$code]['direction'] ?? 'ltr') === 'rtl';
    }

    public function getLocale($code = null)
    {
        $code = $this->normalize($code) ?: $this->getActiveLanguage();
        return $this->languages[$code]['locale'] ?? str_replace('_', '-', Yii::$app->language);
    }

    public function getFallbacks($code = null)
    {
        $code = $this->normalize($code) ?: $this->getActiveLanguage();
        $fallback = $this->languages[$code]['fallback'] ?? $this->defaultLanguage;
        return array_values(array_unique(array_filter([$code, $fallback, $this->defaultLanguage])));
    }

    public function getLanguageUrl($code)
    {
        $route = '/' . Yii::$app->controller->route;
        $params = Yii::$app->request->getQueryParams();
        unset($params['language']);
        return Url::to(array_merge([$route, 'language' => $code], $params));
    }
}
