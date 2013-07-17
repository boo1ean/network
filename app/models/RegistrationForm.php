<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class RegistrationForm for registration form
 * @package app\models
 */
class RegistrationForm extends User
{
    /**
     * @var string Repeat_password for registration
     */
    public $repeat_password;

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

    public function scenarios() {
        return array(
            'default' => array('email', 'password', 'repeat_password', 'first_name', 'last_name')
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
            $this->save();
            return true;
        }

        return false;
    }
}