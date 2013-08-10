<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class RecoverForm for recover password
 * @package app\models
 */
class RecoverForm extends User
{
    /**
     * @var string Password_hash
     */
    public $password_hash;

    /**
     * @var string Repeat_password
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
            array('password_hash', 'validatePasswordHash'),
            array('repeat_password', 'compare', 'compareAttribute'=>'password')
        );
    }

    public function scenarios() {
        return array(
            'default' => array('email', 'password', 'password_hash', 'repeat_password'),
            'firstVisit' => array('email', 'password_hash')
        );
    }

    public function validateEmail() {
        $user = User::findByEmail($this->email);

        if (!$user) {
            $this->addError('email', 'User with this email was not found');
        }
    }

    /**
     * Validation password hash
     */
    public function validatePasswordHash() {
        $user = User::findByEmail($this->email);
        if (!$user || $this->password_hash != $user->password)
            $this->addError('password_hash', 'Incorrect password hash in the recover token');
    }

    public function recover() {
        if ($this->validate()) {
            $user = User::findByEmail($this->email);

            $user->password   = $this->hashPassword($this->password);
            $user->save();
            return true;
        }

        return false;
    }
}