<?php

namespace tests\unit;

use frontend\helpers\MediaUrl;
use PHPUnit\Framework\TestCase;

class MediaUrlTest extends TestCase
{
    public function testExistingImageIsReturned(): void
    {
        self::assertStringEndsWith(
            '/img/portfolio/analytics-platform.webp',
            MediaUrl::image('img/portfolio/analytics-platform.webp', 'img/portfolio/hero-studio.webp')
        );
    }

    public function testMissingImageUsesFallback(): void
    {
        self::assertStringEndsWith(
            '/img/portfolio/hero-studio.webp',
            MediaUrl::image('upload/image/missing.png', 'img/portfolio/hero-studio.webp')
        );
    }
}
