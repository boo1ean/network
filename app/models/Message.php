<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sophie
 * Date: 17.07.13
 * Time: 16:59
 * To change this template use File | Settings | File Templates.
 */

namespace app\models;
use \yii\db\ActiveRecord;
use app\models\Conversation;


class Message extends ActiveRecord
{
    /**
     * @return string name of table in DB
     */
    public static function tableName() {
        return 'messages';
    }

    /**
     * @return array of rules for validation
     */
    public function rules() {
        return array(
            array('user_id, conversation_id, body', 'required'),
            array('conversation_id', 'checkConversation'),
            array('user_id', 'checkUserAccess'),
        );
    }

    /**
     * Check conversation existence
     */
    public function checkConversation() {
        $conversation = Conversation::find($this->conversation_id);
        if (!$conversation) {
            $this->addError('conversation_id', 'Conversation doesn\'t exist.');
        }
    }

    /**
     * Check user access to conversation
     */
    public function checkUserAccess() {
        $conversation = Conversation::find($this->conversation_id);
        if (!$conversation->isConversationMember($this->user_id)) {
            $this->addError('user_id', 'User can\'t send messages to this conversation.');
        }
    }

    /**
     * @return \yii\db\ActiveRelation object contains author of the message
     */
    public function getUser() {
        return $this->hasOne('user', array('id' => 'user_id'));
    }

    /**
     * Set conversation unread for all users except message author
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert) {

        $query = 'UPDATE `user_conversations` SET `unread` = 1 WHERE (`conversation_id` = ' . $this->conversation_id . ' AND NOT(`user_id` = ' . $this->user_id . '))';
        $this->db->createCommand($query)
            ->execute();

        return parent::beforeSave($insert);
    }

}