<?php

namespace frontend\widgets;

use yii\helpers\Html;

final class AdminButton
{
    public static function link($label, $url, string $variant = 'secondary', array $options = []): string
    {
        $options['class'] = self::classes($variant, $options['class'] ?? '');
        return Html::a($label, $url, $options);
    }

    public static function submit($label, string $variant = 'primary', array $options = []): string
    {
        $options['class'] = self::classes($variant, $options['class'] ?? '');
        return Html::submitButton($label, $options);
    }

    public static function classes(string $variant, string $extra = ''): string
    {
        $variants = [
            'primary' => 'd-btn d-btn-primary',
            'secondary' => 'd-btn d-btn-outline',
            'ghost' => 'd-btn d-btn-ghost',
            'danger' => 'd-btn d-btn-error',
            'danger-soft' => 'd-btn d-btn-error d-btn-soft',
            'compact' => 'd-btn d-btn-sm d-btn-square d-btn-ghost',
        ];
        return trim(($variants[$variant] ?? $variants['secondary']) . ' ' . $extra);
    }
}
