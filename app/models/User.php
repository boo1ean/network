<?php

namespace app\models;
use \emberlabs\GravatarLib\Gravatar;
use \yii\db\ActiveRecord;
use \yii\web\Identity;
use \yii\helpers\Security;


class User extends ActiveRecord implements Identity
{
    // User types (column `type` in db)
    const TYPE_ADMIN = 0;
    const TYPE_USER = 1;

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

    public static function getAll() {
        return static::find()
            ->all();
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
        $hash =  Security::generatePasswordHash($password);
        return $hash;
    }

    public function validatePassword($password) {
        return Security::validatePassword($password, $this->password);
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
            if ($key === $skey) {
                return $settings[$skey];
            }
        }

        return false;
    }

    /**
     * Get avatar url
     * @return string URL to gravatar image
     */
    public function getAvatar()
    {
        // Make gravatar
        $gravatar = new Gravatar();
        return $gravatar->buildGravatarURL($this->email);
    }

    /**
     * @return \yii\db\ActiveRelation object with user conversations
     */
    public function getConversations() {
        return $this->hasMany('Conversation', array('id' => 'conversation_id'))
            ->viaTable('user_conversations', array('user_id' => 'id'));
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->password = static::hashPassword($this->password);
            $this->settings = serialize(array());
        }

        return parent::beforeSave($insert);
    }

    /**
     * Find all users except this user
     * @return array
     */
    public function getOtherUsers() {
        return static::find()
            ->where('id <> '. $this->id)
            ->all();
    }

    /**
     * If first_name and last_name set, return first_name and last_name
     * else return email
     * @return mixed|string
     */
    public function getUserName() {
        if(isset($this->first_name) && isset($this->last_name)) {
            return $this->first_name . ' ' . $this->last_name;
        } else {
            return $this->email;
        }
    }

    public static function getUserNameById($id) {
        $user = User::find($id);

        if(isset($user->first_name) && isset($user->last_name)) {
            return $user->first_name . ' ' . $user->last_name;
        } else {
            return $user->email;
        }
    }
}

