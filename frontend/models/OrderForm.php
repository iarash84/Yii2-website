<?php

namespace frontend\models;



use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class OrderForm extends Model
{
    public $name;
    public $email;
    public $company;
    public $phoneNumber;
    public $website;
    public $description;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'description','phoneNumber'], 'required'],
            [['website'], 'string', 'max' => 255],
            [['company'] , 'string', 'max' => 255 ],
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
            'company' => Yii::t('app', 'Company'),
            'email' => Yii::t('app', 'Email'),
            'phoneNumber' => Yii::t('app', 'Phone Number'),
            'website' => Yii::t('app', 'Website'),
            'description' => Yii::t('app', 'Description project'),
            'verifyCode' => Yii::t('app', 'Verify Code'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function saveOrder()
    {
        $orderModel = new Order();
        $orderModel->name = $this->name;
        $orderModel->email = $this->email;
        $orderModel->company = $this->company;
        $orderModel->phoneNumber = $this->phoneNumber;
        $orderModel->website = $this->website;
        $orderModel->description = $this->description;
        if($orderModel->save())
            return true;
        return false;
    }
}
