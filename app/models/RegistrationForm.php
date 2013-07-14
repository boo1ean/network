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
            array('password', 'compare', 'compareAttribute'=>'repeat_password'),
        );
    }

    public function registration() {
        if ($this->validate()) {
            // TODO: Replace by User::findByEmail when it will implemented
            $user = User::find(array('email' => $this->email));

            return true;
        }

        return false;
    }
}