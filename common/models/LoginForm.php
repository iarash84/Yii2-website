<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'User Name'),
            'password' => Yii::t('app', 'Password'),
            'rememberMe' => Yii::t('app', 'Remember Me'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->isRateLimited()) {
            $this->addError('password', Yii::t('app', 'Too many login attempts. Please try again later.'));
            return false;
        }
        $model = new Log();

        $model->user = mb_substr((string) $this->username, 0, 50);
        $model->ip = $this->clientIp();
        $model->userAgent = mb_substr((string) Yii::$app->request->userAgent, 0, 1000);


        if (!$this->validate()) {
            $model->success = 0;
            $model->save(false);
            $this->recordFailedAttempt();
            return false;
        }

        $model->success = 1;
        $model->save(false);
        Yii::$app->cache->delete($this->rateLimitKey());
        $loggedIn = Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        if ($loggedIn) {
            Yii::$app->session->regenerateID(true);
        }
        return $loggedIn;

    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    private function isRateLimited()
    {
        return (int) Yii::$app->cache->get($this->rateLimitKey()) >= 5;
    }

    private function recordFailedAttempt()
    {
        $key = $this->rateLimitKey();
        Yii::$app->cache->set($key, (int) Yii::$app->cache->get($key) + 1, 900);
    }

    private function rateLimitKey()
    {
        return 'login:' . hash('sha256', mb_strtolower(trim((string) $this->username)) . '|' . $this->clientIp());
    }

    private function clientIp()
    {
        return mb_substr((string) Yii::$app->request->userIP, 0, 45);
    }
}
