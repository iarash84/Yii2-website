<?php

namespace frontend\components;

use Yii;

class TextCaptcha
{
    private const SESSION_KEY = 'publicForm.textCaptcha';

    public static function question()
    {
        if (YII_ENV_TEST) {
            return Yii::t('app', 'Enter the test verification code.');
        }

        $challenge = Yii::$app->session->get(self::SESSION_KEY);
        if (!is_array($challenge) || !isset($challenge['question'], $challenge['answer'])) {
            $left = random_int(2, 9);
            $right = random_int(1, 9);
            $challenge = [
                'question' => Yii::t('app', 'What is {left} + {right}?', [
                    'left' => $left,
                    'right' => $right,
                ]),
                'answer' => (string) ($left + $right),
            ];
            Yii::$app->session->set(self::SESSION_KEY, $challenge);
        }
        return $challenge['question'];
    }

    public static function validate($value)
    {
        if (YII_ENV_TEST) {
            return hash_equals('testme', trim((string) $value));
        }

        $challenge = Yii::$app->session->get(self::SESSION_KEY);
        if (!is_array($challenge) || !isset($challenge['answer'])) {
            return false;
        }
        $valid = hash_equals((string) $challenge['answer'], trim((string) $value));
        if ($valid) {
            Yii::$app->session->remove(self::SESSION_KEY);
        }
        return $valid;
    }
}
