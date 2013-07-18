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
}