<?php

namespace frontend\components;

use frontend\models\SystemSetting;
use yii\i18n\Formatter;

class LocalizedFormatter extends Formatter
{
    public function asDate($value, $format = null)
    {
        if (!$this->usesJalali()) {
            return parent::asDate($value, $format);
        }
        $timestamp = $this->timestamp($value);
        if ($timestamp === null) {
            return $this->nullDisplay;
        }
        [$year, $month, $day] = self::gregorianToJalali((int) date('Y', $timestamp), (int) date('n', $timestamp), (int) date('j', $timestamp));
        return sprintf('%04d/%02d/%02d', $year, $month, $day);
    }

    public function asDatetime($value, $format = null)
    {
        if (!$this->usesJalali()) {
            return parent::asDatetime($value, $format);
        }
        $timestamp = $this->timestamp($value);
        return $timestamp === null ? $this->nullDisplay : $this->asDate($timestamp) . ' ' . date('H:i', $timestamp);
    }

    public function asYear($value)
    {
        if (!$this->usesJalali()) {
            $timestamp = $this->timestamp($value);
            return $timestamp === null ? $this->nullDisplay : date('Y', $timestamp);
        }
        return explode('/', $this->asDate($value))[0];
    }

    private function usesJalali()
    {
        try {
            return SystemSetting::getValue('date_calendar', 'gregorian') === 'jalali';
        } catch (\Throwable $exception) {
            return false;
        }
    }

    private function timestamp($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_numeric($value)) {
            return (int) $value;
        }
        $timestamp = strtotime((string) $value);
        return $timestamp === false ? null : $timestamp;
    }

    public static function gregorianToJalali($gy, $gm, $gd)
    {
        $gDays = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $gy2 = $gm > 2 ? $gy + 1 : $gy;
        $days = 355666 + 365 * $gy + intdiv($gy2 + 3, 4) - intdiv($gy2 + 99, 100) + intdiv($gy2 + 399, 400) + $gd + $gDays[$gm - 1];
        $jy = -1595 + 33 * intdiv($days, 12053);
        $days %= 12053;
        $jy += 4 * intdiv($days, 1461);
        $days %= 1461;
        if ($days > 365) {
            $jy += intdiv($days - 1, 365);
            $days = ($days - 1) % 365;
        }
        return $days < 186
            ? [$jy, 1 + intdiv($days, 31), 1 + ($days % 31)]
            : [$jy, 7 + intdiv($days - 186, 30), 1 + (($days - 186) % 30)];
    }
}
