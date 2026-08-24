<?php

namespace frontend\services;

use frontend\models\SystemSetting;
use Yii;

class NotificationService
{
    public static function formSubmitted($type, array $data)
    {
        if (!filter_var(SystemSetting::getValue('notify_' . $type, '1'), FILTER_VALIDATE_BOOLEAN)) {
            return false;
        }
        $to = SystemSetting::getValue('notification_email', Yii::$app->params['adminEmail']);
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        self::configureMailer();
        $body = "New {$type} submission\n\n";
        foreach ($data as $key => $value) {
            if (is_scalar($value) && !in_array($key, ['verifyCode', 'resume'], true)) {
                $body .= $key . ': ' . strip_tags((string) $value) . "\n";
            }
        }
        try {
            return Yii::$app->mailer->compose()->setTo($to)->setFrom([SystemSetting::getValue('mail_from_email', Yii::$app->params['supportEmail']) => SystemSetting::getValue('mail_from_name', Yii::$app->name)])->setSubject("New {$type} submission")->setTextBody($body)->send();
        } catch (\Throwable $e) {
            Yii::warning('Form notification failed: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }
    public static function configureMailer()
    {
        $host = SystemSetting::getValue('smtp_host');
        if (!$host) {
            return;
        }
        Yii::$app->mailer->useFileTransport = filter_var(SystemSetting::getValue('mail_file_transport', '1'), FILTER_VALIDATE_BOOLEAN);
        Yii::$app->mailer->setTransport(['class' => 'Swift_SmtpTransport', 'host' => $host, 'username' => SystemSetting::getValue('smtp_username'), 'password' => SystemSetting::getValue('smtp_password'), 'port' => (int) SystemSetting::getValue('smtp_port', 587), 'encryption' => SystemSetting::getValue('smtp_encryption', 'tls') ?: null]);
    }
}
