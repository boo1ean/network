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
     * Add subscribed user to conversation
     * @param $user User
     */
    public function addSubscribed($user) {
        // Refresh is needed here because without it conversation continues to be private despite the number of users
        $this->refresh();
        $conversation = $this;

        // Check if user exists and isn't conversation member
        if ($user->isNewRecord || ($this->isConversationMember($user->id))) {
            return 0;
        }

        // If conversation is private and becomes common after users invitation
        if ($this->isPrivate() && ((count($this->users) + 1) > 2 )) {
            // If there are messages in conversation, copy it as multichat
            if ($this->messages) {
                $conversation = $this->copyToMultiChat();
            } else {    // If there are no messages, make its type not private
                $conversation->private = 0;
                $conversation->save();
            }
        }
        // Link users to conversation
        $conversation->link('users', $user);
        return $conversation;
    }

    /**
     * Creates a copy of conversation with its members
     * @return Conversation new conversation
     */
    public function copyToMultiChat() {
        $newConversation = new Conversation(array(
            'creator' => $this->creator,
            'private' => 0,
            'title'   => $this->title
        ));
        $newConversation->save();
        $newConversation->refresh();
        foreach($this->users as $user) {
            $newConversation->link('users', $user);
        }
        return $newConversation;
    }

    public function deleteMember($user) {

        if(is_numeric($user)) {
            $user = User::find($user);
        }

        $this->unlink('users', $user);
    }

    /**
     * @return object /app/models/User
     */
    public function getCreator() {
        $creator = $this->hasOne('User', array('id' => 'creator'))->one();
        return $creator ? $creator : new User();
    }

    /**
     * @return int time of last message
     */
    public function getLastMessageTime() {
        return $this->hasMany('Message', array('conversation_id' => 'id'))
            ->max('datetime');
    }

    /**
     * @return \yii\db\ActiveRelation object contains conversation messages
     */
    public function getMessages() {
        return $this->hasMany('Message', array('conversation_id' => 'id'));
    }

    /**
     * @return array of users don't participate in conversation
     */
    public function getUnsubscribedUsers() {
        $id = array();
        foreach($this->users as $user)
            $id[] = $user->id;
        return User::find()
            ->where(array('not in', 'id', $id))
            ->all();
    }

    /**
     * @return \yii\db\ActiveRelation object contains conversation users
     */
    public function getUsers() {
        return $this->hasMany('User', array('id' => 'user_id'))
            ->viaTable('user_conversations', array('conversation_id' => 'id'));
    }

    /**
     * @param $user_id
     * @return bool true if user is a member of conversation, false - if not
     */
    public function isConversationMember($userId) {
        $user = $this->hasMany('User', array('id' => 'user_id'))
            ->viaTable('user_conversations', array('conversation_id' => 'id'))
            ->where('id = ' . $userId)
            ->one();
        return !empty($user);
    }

    /**
     * @return bool value, true if conversation is private, false if not
     */
    public function isPrivate() {
        if ($this->private) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns if conversation was read by specified user
     * @param integer $userId
     * @return bool true if unread, else false
     */
    public function isUnread($userId) {
        $exist =  $this->createQuery()
            ->select('user_conversations.*')
            ->from('user_conversations')
            ->where('conversation_id = ' . $this->id)
            ->andWhere('user_id = ' . $userId)
            ->andWhere('unread = 1')
            ->exists();

        return $exist;
    }

    /**
     * Set conversation as read for specified user
     * @param integer $userId
     */
    public function markAsRead($userId) {

        $query = 'UPDATE `user_conversations` SET `unread` = 0 WHERE `conversation_id` = ' . $this->id . ' AND `user_id` = ' . $userId;
        $this->db->createCommand($query)
            ->execute();
    }

    /**
     * @return string name of table in DB
     */
    public static function tableName() {
        return 'conversations';
    }
}