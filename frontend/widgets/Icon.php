<?php

namespace frontend\widgets;

use yii\helpers\Html;

class Icon
{
    private const PATHS = [
        'dashboard' => 'M3 13h8V3H3v10Zm0 8h8v-6H3v6Zm10 0h8V11h-8v10Zm0-18v6h8V3h-8Z',
        'edit' => 'm4 16.5-.5 4 4-.5L19 8.5 15.5 5 4 16.5ZM14 6.5l3.5 3.5M13 20h8',
        'delete' => 'M4 7h16M9 7V4h6v3m3 0-1 14H7L6 7m4 4v6m4-6v6',
        'plus' => 'M12 5v14M5 12h14',
        'settings' => 'M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm7.4-3.5a7.6 7.6 0 0 0-.1-1l2-1.6-2-3.4-2.4 1a8 8 0 0 0-1.8-1L14.8 3h-4l-.4 2.8a8 8 0 0 0-1.8 1L6.2 6l-2 3.4 2 1.6a7.6 7.6 0 0 0 0 2l-2 1.6 2 3.4 2.4-1a8 8 0 0 0 1.8 1l.4 3h4l.4-3a8 8 0 0 0 1.8-1l2.4 1 2-3.4-2-1.6a7.6 7.6 0 0 0 .1-1Z',
        'posts' => 'M5 3h14v18H5V3Zm3 5h8M8 12h8m-8 4h5',
        'users' => 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m7-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.9m-2-12a4 4 0 0 1 0 7.8',
        'external' => 'M14 3h7v7m0-7-10 10m8 0v7H4V5h7',
        'image' => 'M3 5h18v14H3V5Zm0 11 5-5 4 4 2-2 7 6M16 9h.01',
        'arrow-up' => 'm6 10 6-6 6 6M12 4v16',
        'arrow-down' => 'm6 14 6 6 6-6M12 20V4',
        'inbox' => 'M4 4h16v16H4V4Zm0 11h5l2 2h2l2-2h5',
        'briefcase' => 'M3 7h18v13H3V7Zm5 0V4h8v3m-13 5h18',
        'menu' => 'M4 6h16M4 12h16M4 18h16',
    ];

    public static function show($name, array $options = [])
    {
        $path = self::PATHS[$name] ?? self::PATHS['posts'];
        $options = array_merge([
            'class' => 'icon',
            'viewBox' => '0 0 24 24',
            'width' => 20,
            'height' => 20,
            'fill' => 'none',
            'stroke' => 'currentColor',
            'stroke-width' => 1.8,
            'stroke-linecap' => 'round',
            'stroke-linejoin' => 'round',
            'aria-hidden' => 'true',
            'focusable' => 'false',
        ], $options);
        return Html::tag('svg', Html::tag('path', '', ['d' => $path]), $options);
    }
}
