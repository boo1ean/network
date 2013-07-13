<?php

namespace app\models;
use \yii\db\ActiveRecord;
use \yii\web\Identity;

/**
 * Class User
 * @package app\models
 * @property integer $id;
 * @property string $email
 * @property string $first_name;
 * @property string $last_name;
 * @property string $password;
 * @property integer $type;
 */
class User extends ActiveRecord implements Identity
{
    // Constants for user types
    const TYPE_ADMIN = 0;
    const TYPE_USER = 1;
    // Attributes
    public $id;
    public $email;
    public $first_name;
    public $last_name;
    public $password;
    public $type;

    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return array(
            array('email, password', 'required', 'on' => 'login'),
            array('email, first_name, last_name, password', 'required', 'on' => 'register')
        );
    }

    public function scenarios()
    {
        return array(
            'login' => array('email', 'password'),
            'register' => array('email','first_name', 'last_name', 'password'),
        );
    }

    public static function findIdentity($id)
    {
        return static::find($id);
    }

    public function getId()
    {
         return $this->id;
    }

    public function getAuthKey()
    {
       // return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
       // return $this->authKey === $authKey;
    }

    public static function addUser($email, $first_name, $last_name, $password, $type = self::TYPE_USER)
    {
        $user = new User();
        $user->scenario ='register';
        $user->email = $email;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->password = $password;
        $user->type = $type;
        $user->save();
    }
}