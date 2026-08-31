<?php

namespace frontend\widgets;

use Yii;
use yii\helpers\Html;

final class StatusBadge
{
    public static function boolean($value): string
    {
        $active = (bool) $value;
        return Html::tag('span', $active ? Yii::t('app', 'Enabled') : Yii::t('app', 'Disabled'), [
            'class' => 'd-badge ' . ($active ? 'd-badge-success' : 'd-badge-ghost'),
        ]);
    }

    public static function publication(string $status, string $label): string
    {
        $classes = [
            'published' => 'd-badge-success',
            'scheduled' => 'd-badge-info',
            'draft' => 'd-badge-ghost',
        ];
        return Html::tag('span', Html::encode($label), ['class' => 'd-badge ' . ($classes[$status] ?? 'd-badge-warning')]);
    }
}
