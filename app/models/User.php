<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sophie
 * Date: 12.07.13
 * Time: 23:38
 * To change this template use File | Settings | File Templates.
 */
namespace app\models;
use \yii\db\ActiveRecord;
use \yii\web\Identity;

class User extends ActiveRecord implements Identity
{
    public $id;
    public $email;
    public $first_name;
    public $last_name;
    public $password;
    public $type;
    /**
     * @return tablename
     */
    public static function tableName()
    {
        return 'users';
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

}