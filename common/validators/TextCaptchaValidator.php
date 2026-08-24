<?php

namespace common\validators;

use frontend\components\TextCaptcha;
use Yii;
use yii\validators\Validator;

class TextCaptchaValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if (!TextCaptcha::validate($model->$attribute)) {
            $this->addError($model, $attribute, Yii::t('app', 'The verification answer is incorrect.'));
        }
    }
}
