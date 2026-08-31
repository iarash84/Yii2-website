<?php

namespace tests\integration;

use frontend\models\DashboardPreference;
use PHPUnit\Framework\TestCase;

class DashboardPreferenceTest extends TestCase
{
    public function testLayoutIsRestrictedToKnownWidgetsAndCompleted(): void
    {
        $layout = DashboardPreference::normalize([
            'order' => ['analytics', 'unknown', 'analytics'],
            'hidden' => ['metrics', 'invalid'],
            'collapsed' => ['analytics', 'invalid'],
        ]);
        self::assertSame(['analytics', 'metrics', 'quick_actions', 'recent_activity', 'system_status'], $layout['order']);
        self::assertSame(['metrics'], $layout['hidden']);
        self::assertSame(['analytics'], $layout['collapsed']);
    }
}
