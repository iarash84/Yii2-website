<?php

namespace common\components;

use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransportFactory;

class SmtpTransportFactory
{
    public static function create($host, $port, $username = '', $password = '', $encryption = '')
    {
        $encryption = strtolower((string) $encryption);
        $options = [];
        if ($encryption === 'tls') {
            $options['require_tls'] = true;
        } elseif ($encryption === '') {
            $options['auto_tls'] = false;
        }

        $dsn = new Dsn(
            $encryption === 'ssl' ? 'smtps' : 'smtp',
            (string) $host,
            (string) $username ?: null,
            (string) $password ?: null,
            (int) $port,
            $options
        );
        $transport = (new EsmtpTransportFactory())->create($dsn);
        if (!$transport instanceof EsmtpTransport) {
            throw new \RuntimeException('The configured transport is not an SMTP transport.');
        }

        return $transport;
    }
}
