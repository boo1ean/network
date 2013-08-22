<?php

namespace app\models;
use \emberlabs\GravatarLib\Gravatar;
use \yii\db\ActiveRecord;
use yii\db\ActiveRelation;
use yii\db\mssql\PDO;
use \yii\web\Identity;
use \yii\helpers\Security;
use yii\db\Expression;


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
     * @param string $email custom email
     * @return string URL to gravatar image
     */
    public function getAvatar($email = null) {
        if(is_null($email)) {
            $email = $this->email;
        }
        // Make gravatar
        $gravatar = new Gravatar();
        $gravatar->setDefaultImage('wavatar');
        return $gravatar->buildGravatarURL($email);
    }

    /**
     * Return all user conversation
     * Conversations sort by unread and datetime of last message
     * First are unread conversation, next - read
     * Unread and read conversations in turn are sorted by datetime inside groups
     * @return \yii\db\ActiveRelation object with user conversations
     */
    public function getConversations() {

        $query = Conversation::createQuery();
        $result = $query->select('conversations.*')
            ->from('users')
            ->join('inner join', 'user_conversations', 'user_conversations.user_id = users.id')
            ->join('inner join', 'conversations', 'user_conversations.conversation_id = conversations.id')
            ->join('left join', 'messages', 'messages.conversation_id = conversations.id')
            ->where('user_conversations.user_id = ' . $this->id)
            ->groupBy('conversations.id')
            ->orderBy('unread desc, max(messages.datetime) desc')
            ->all();

        return $result;
    }

    /**
     * Get unread notifications
     */
    public function getNotifications() {
        // Get all unread conversations
        $queryConversations = Conversation::createQuery();
        $conversations = $queryConversations->select('conversations.*')
            ->from('users')
            ->join('inner join', 'user_conversations', 'user_conversations.user_id = users.id')
            ->join('inner join', 'conversations', 'user_conversations.conversation_id = conversations.id')
            //->join('left join', 'messages', 'messages.conversation_id = conversations.id')
            ->where('users.id = ' . $this->id)
            ->andWhere('user_conversations.unread = 1')
            ->groupBy('conversations.id')
            //->orderBy('max(messages.datetime) desc')
            ->all();

        // Get all unread events
        $queryEvents = Event::createQuery();
        $events = $queryEvents->select('events.*')
            ->from('users')
            ->join('inner join', 'user_events', 'user_events.user_id = users.id')
            ->join('inner join', 'events', 'user_events.event_id = events.id')
            ->where('users.id = ' . $this->id)
            ->andWhere('user_events.unread = 1')
            ->groupBy('events.id')
            ->all();

        $result = array_merge($conversations, $events);

        return $result;
    }

    /**
     * @return int count of unread notifications
     */
    public function getNotificationsCount() {
        // Unread conversations count
        $conversationsCount = Conversation::createQuery()
            ->select('*')
            ->from('user_conversations')
            ->where('user_conversations.user_id = ' . $this->id)
            ->andWhere('user_conversations.unread = 1')
            ->count();

        // Unread events count
        $eventsCount = Event::createQuery()
            ->select('*')
            ->from('user_events')
            ->where('user_events.user_id = ' . $this->id)
            ->andWhere('user_events.unread = 1')
            ->count();

        return $conversationsCount + $eventsCount;
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

    public function getIsOnline(){
        $userActivity = $this->last_activity;
        $now = time();
        $minutes = round(($now - strtotime($userActivity))/60);
        return $minutes < 10;
    }
}

