<?php

namespace frontend\helpers;

use Yii;
use yii\helpers\Url;

class MediaUrl
{
    public static function image($path, $fallback)
    {
        $relativePath = self::existingRelativePath($path) ?: self::existingRelativePath($fallback);

        return Url::to('@web/' . $relativePath);
    }

    private static function existingRelativePath($path)
    {
        $relativePath = ltrim(str_replace('\\', '/', (string) $path), '/');
        if ($relativePath === '' || strpos($relativePath, '..') !== false) {
            return null;
        }

        $absolutePath = Yii::getAlias('@webroot/' . $relativePath);

        return is_file($absolutePath) ? $relativePath : null;
    }
}
