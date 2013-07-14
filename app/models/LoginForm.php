<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class LoginForm for login form
 * @package app\models
 */
class LoginForm extends Model
{

    /**
     * @var integer Number of seconds that the user can remain in logged-in status.
     * Defaults to 0, meaning login till the user closes the browser or the session is manually destroyed.
     * If greater than 0 and [[enableAutoLogin]] is true, cookie-based login will be supported.
     */
    const DEFAULT_LOGIN_DURATION = 0;
    /**
     * @var string Email for login
     */
    public $email;

    /**
     * @var string Password for login
     */
    public $password;

    /**
     * @var string Captcha for login
     */
    public $captcha;

    /**
     * @return validation rules array
     */
    public function rules() {
        return array(
            array('email, password, captcha', 'required'),
            array('email', 'email'),
            array('password', 'validatePassword'),
            //array('captcha', 'captcha'),
        );
    }

    /**
     * Validation password
     */
    public function validatePassword() {
        $user = User::findByEmail($this->email);

        // TODO: Need add password validation
        if (!$user) {
            $this->addError('email', 'Incorrect email');
        } elseif (!$user/*->validatePassword($this->password)*/) {        // TODO: Implement password check
            $this->addError('password', 'Incorrect password');
        }
    }

    public function login() {
        if ($this->validate()) {
            $user = User::findByEmail($this->email);

            Yii::$app->getUser()->login($user, DEFAULT_LOGIN_DURATION);
            return true;
        }
        return false;
    }
}