<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class SystemSetting extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%system_setting}}';
    }
    public function behaviors()
    {
        return [TimestampBehavior::class];
    }
    public function rules()
    {
        return [[['key'], 'required'], [['value'], 'string'], [['is_secret', 'updated_by'], 'integer'], [['key'], 'string', 'max' => 100]];
    }
    public static function getValue($key, $default = null)
    {
        $model = self::findOne($key);
        if (!$model) {
            return $default;
        }
        if (!$model->is_secret) {
            return $model->value;
        }
        try {
            return Yii::$app->security->decryptByPassword(base64_decode($model->value), self::secret());
        } catch (\Throwable $e) {
            return $default;
        }
    }
    public static function put($key, $value, $secret = false)
    {
        $model = self::findOne($key) ?: new self(['key' => $key]);
        $model->is_secret = $secret ? 1 : 0;
        $model->updated_by = Yii::$app->user->id ?: null;
        $model->value = $secret && $value !== '' ? base64_encode(Yii::$app->security->encryptByPassword($value, self::secret())) : (string) $value;
        return $model->save();
    }
    private static function secret()
    {
        return hash('sha256', Yii::$app->request->cookieValidationKey ?: Yii::$app->id);
    }
}
