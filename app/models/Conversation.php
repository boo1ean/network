<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sophie
 * Date: 17.07.13
 * Time: 15:23
 * To change this template use File | Settings | File Templates.
 */

namespace app\models;
use \yii\db\ActiveRecord;


class Conversation extends ActiveRecord
{
    /**
     * @return string name of table in DB
     */
    public static function tableName() {
        return 'conversations';
    }

    /**
     * @return \yii\db\ActiveRelation object contains conversation messages
     */
    public function getMessages() {
        return $this->hasMany('Message', array('conversation_id' => 'id'));
    }

    /**
     * @return \yii\db\ActiveRelation object contains conversation users
     */
    public function getUsers() {
        return $this->hasMany('User', array('id' => 'user_id'))
            ->viaTable('user_conversations', array('conversation_id' => 'id'));
    }

    /**
     * @return array of users don't participate in conversation
     */
    public function getUnsubscribedUsers() {
        foreach($this->users as $user)
            $id[] = $user->id;
        return User::find()
            ->where(array('not in', 'id', $id))
            ->all();
    }

    public function addSubscribed($idArray) {
        foreach($idArray as $key => $userId) {
            $this->link('users', User::find($userId));
        }
    }

    /**
     * @param $user_id
     * @return bool true if user is a member of conversation, false - if not
     */
    public function isConversationMember($user_id) {
        $user = $this->hasMany('User', array('id' => 'user_id'))
            ->viaTable('user_conversations', array('conversation_id' => 'id'))
            ->where('id = ' . $user_id)
            ->one();
        return !empty($user);
    }

    /**
     * @return bool value, true if conversation is private, false if not
     */
    public function isPrivate() {
        $usersCount = count($this->users);
        if ($usersCount <= 2) {
            return true;
        } else {
            return false;
        }

    }
}