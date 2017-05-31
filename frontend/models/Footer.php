<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;
use yii\web\UploadedFile;

/**
 * Signup form
 */
class Footer extends Model
{
    public $companyName;
    public $address;
    public $postalCode;
    public $phoneNumber;
    public $faxNumber;
    public $email;
    public $workingHours;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['user_id'], 'required'],
            [['address', 'postalCode', 'phoneNumber', 'faxNumber', 'email', 'workingHours', 'companyName'], 'required'],
            [['address', 'postalCode', 'phoneNumber', 'faxNumber', 'email', 'workingHours', 'companyName'], 'string'],
            [['address', 'postalCode', 'phoneNumber', 'faxNumber', 'email', 'workingHours', 'companyName'], 'safe'],
            [['address', 'postalCode', 'phoneNumber', 'faxNumber', 'email', 'workingHours', 'companyName'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'address' => Yii::t('app', 'Address'),
            'postalCode' => Yii::t('app', 'Postal Code'),
            'phoneNumber' => Yii::t('app', 'PhoneNumber'),
            'faxNumber' => Yii::t('app', 'FaxNumber'),
            'email' => Yii::t('app', 'Email'),
            'workingHours' => Yii::t('app', 'Working Hours'),
            'companyName' => Yii::t('app','Company Name')
        ];
    }


}

