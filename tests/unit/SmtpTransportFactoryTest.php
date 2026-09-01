<?php

namespace tests\unit;

use common\components\SmtpTransportFactory;
use PHPUnit\Framework\TestCase;

class SmtpTransportFactoryTest extends TestCase
{
    public function testCreatesRequiredStartTlsTransport(): void
    {
        $transport = SmtpTransportFactory::create(
            'smtp.example.test',
            587,
            'mailer',
            'secret',
            'tls'
        );

        self::assertSame('mailer', $transport->getUsername());
        self::assertTrue($transport->isTlsRequired());
        self::assertFalse($transport->getStream()->isTLS());
        self::assertSame('smtp://smtp.example.test:587', (string) $transport);
        self::assertStringNotContainsString('secret', (string) $transport);
    }

    public function testCreatesImplicitTlsTransport(): void
    {
        $transport = SmtpTransportFactory::create('smtp.example.test', 465, '', '', 'ssl');

        self::assertTrue($transport->getStream()->isTLS());
        self::assertSame('smtps://smtp.example.test', (string) $transport);
    }
}
