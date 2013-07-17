<?php

namespace app\models;
use \yii\db\ActiveRecord;
use \yii\web\Identity;
use \yii\helpers\SecurityHelper;


class User extends ActiveRecord implements Identity
{
    public static function tableName() {
        return 'users';
    }

    public static function findIdentity($id) {
        return static::find($id);
    }

    public static function findByEmail($email) {
        return static::find()
            ->where(array('email' => $email))
            ->one();
    }

    public function getId() {
         return $this->id;
    }

    public function getAuthKey() {
       // return $this->authKey;
    }

    public function validateAuthKey($authKey) {
       // return $this->authKey === $authKey;
    }

    public static function hashPassword($password) {
        $hash =  SecurityHelper::generatePasswordHash($password);
        return $hash;
    }

    public function validatePassword($password) {
        return SecurityHelper::validatePassword($password, $this->password);
    }

    public function getSetting() {
        return unserialize($this->settings);
    }

    public function setSetting($settingArray) {
        $this->settings = serialize($settingArray);
    }

    /**
     * Add setting pair $key=>$value to user model
     * @param string $key Setting key
     * @param string $value Setting value
     */
    public function addSetting($key, $value) {
        $setting = $this->getSetting();
        $setting[$key] = $value;
        $this->setSetting($setting);

    }

    /**
     * Search setting by key
     * @param string $key Setting key
     * @return mixed|bool Setting or false if setting by key not found
     */
    public function searchSetting($key) {
        $settings = $this->getSetting();
        foreach ($settings as $skey => $value) {
            if ($key == $skey)
                return $settings[$skey];
        }

        return false;
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord)
        {
            $this->password = static::hashPassword($this->password);
            $this->settings = serialize(array());
        }
        return parent::beforeSave($insert);
    }
}

