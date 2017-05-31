<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class OpportunityForm extends Model
{
    public $name;
    public $email;
    public $phoneNumber;
    public $resume;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email'], 'required'],
            [['resume'], 'file', 'extensions' => 'pdf'],
            // email has to be a valid email address
            ['email', 'email'],
            ['email', 'filter', 'filter' => 'trim'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
            ['phoneNumber', 'integer', 'integerOnly'=>true, 'min'=>10],
            ['phoneNumber', 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name and family'),
            'email' => Yii::t('app', 'Email'),
            'phoneNumber' => Yii::t('app', 'Phone Number'),
            'resume' => Yii::t('app', 'Resume'),
            'verifyCode' => Yii::t('app', 'Verify Code'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function saveOpportunity()
    {
        $opportunityModel = new Opportunity();
        $opportunityModel->name = $this->name;
        $opportunityModel->email = $this->email;
        $opportunityModel->phoneNumber = $this->phoneNumber;
        if($opportunityModel->save())
            return true;
        return false;
    }
}
