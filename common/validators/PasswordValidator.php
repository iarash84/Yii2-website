<?php

namespace common\validators;

use yii\validators\RegularExpressionValidator;

class PasswordValidator extends RegularExpressionValidator
{
    public $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{12,72}$/';
    public $message = 'رمز عبور باید ۱۲ تا ۷۲ نویسه و شامل حرف کوچک، حرف بزرگ، عدد و نماد باشد.';
}
