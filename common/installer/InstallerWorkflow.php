<?php

namespace common\installer;

class InstallerWorkflow
{
    public const FIRST_STEP = 1;
    public const LAST_FORM_STEP = 8;
    public const FINISHED_STEP = 9;

    public static function normalizeStep($step): int
    {
        $step = (int) $step;
        if (in_array($step, [self::FINISHED_STEP, 10], true)) {
            return self::FINISHED_STEP;
        }

        return max(self::FIRST_STEP, min(self::LAST_FORM_STEP, $step));
    }

    public static function checksPassed(array $checks): bool
    {
        return !in_array(false, $checks, true);
    }

    public static function restartConfirmed($value): bool
    {
        return (string) $value === '1';
    }

    public static function isHttps($value): bool
    {
        if ($value === null) {
            return false;
        }

        return !in_array(strtolower((string) $value), ['', 'off', '0'], true);
    }

    public static function goBack(int $step, array &$session): int
    {
        $step = max(self::FIRST_STEP, $step - 1);
        if ($step <= 7) {
            unset($session['installer_admin']);
        }
        if ($step <= 3) {
            unset($session['installer_database']);
        }

        return $step;
    }
}
