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
            array('password', 'compare', 'compareAttribute'=>'repeat_password'),
        );
    }

    public function registration() {
        if ($this->validate()) {
            parent::save();

            return true;
        }

        return false;
    }
}