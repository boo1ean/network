<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class RegistrationForm for registration form
 * @package app\models
 */
class RegistrationForm extends Model
{
    /**
     * @var string Email for registration
     */
    public $email;

    /**
     * @var string Password for registration
     */
    public $password;

    /**
     * @var string Repeat_password for registration
     */
    public $repeat_password;

    /**
     * @var string First_name for registration
     */
    public $first_name;

    /**
     * @var string Last_name for registration
     */
    public $last_name;

    /**
     * @return validation rules array
     */
    public function rules() {
        return array(
            array('email, password, repeat_password', 'required'),
            array('email', 'email'),
            array('email', 'validateEmail'),
            array('repeat_password', 'compare', 'compareAttribute'=>'password'),
        );
    }

    public function validateEmail() {
        $user = User::findByEmail($this->email);

        if ($user) {
            $this->addError('email', 'User with this email already exist');
        }
    }

    public function registration() {
        if ($this->validate()) {

            User::addUser($_POST['RegistrationForm']['email'], $_POST['RegistrationForm']['first_name'], $_POST['RegistrationForm']['last_name'], $_POST['RegistrationForm']['password'], User::TYPE_USER);

            return true;
        }

        return false;
    }
}