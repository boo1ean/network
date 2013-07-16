<?php

namespace app\models;
use \yii\db\ActiveRecord;
use \yii\web\Identity;
use \yii\helpers\SecurityHelper;


class User extends ActiveRecord implements Identity
{
    // Constants for user types
    const TYPE_ADMIN = 0;
    const TYPE_USER = 1;

    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return array(
            array('email, password', 'required', 'on' => 'login'),
            array('email, password', 'required', 'on' => 'register')
        );
    }

    public function scenarios()
    {
        return array(
            'login' => array('email', 'password'),
            'register' => array('email', 'password'),
        );
    }

    public static function findIdentity($id)
    {
        return static::find($id);
    }

    public static function findByEmail($email)
    {
        return static::find()
            ->where(array('email' => $email))
            ->one();
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
        $user->password = static::hashPassword($password);
        $user->type = $type;
        $user->settings = serialize([]);
        $user->save();
    }

    public static function hashPassword($password)
    {
        $hash =  SecurityHelper::generatePasswordHash($password);
        return $hash;
    }

    public function getSetting() {
        return unserialize($this->settings);
    }
}