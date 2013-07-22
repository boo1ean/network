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

    /**
     * Add subscribed users to conversation
     * @param $idArray array of id to subscribe
     */
    public function addSubscribed($idArray) {
        $conversation = $this;
        // If conversation is private and becomes common after users invitation
        if ($this->isPrivate() && ((count($this->users) + count($idArray)) > 2 )) {
            if ($this->messages) {
                $conversation = $this->copyToMultiChat();
            } else {
                $conversation->private = 0;
                $conversation->save();
                $conversation->refresh();
            }
        }
        foreach($idArray as $key => $userId) {
            $conversation->link('users', User::find($userId));
        }
        return $conversation;
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
     * Creates a copy of conversation with its members
     * @return Conversation new conversation
     */
    public function copyToMultiChat() {
        $newConversation = new Conversation(array(
            'title'     => $this->title,
            'private'   => 0,
        ));
        $newConversation->save();
        $newConversation->refresh();
        foreach($this->users as $user) {
            $newConversation->link('users', $user);
        }
        return $newConversation;
    }
}