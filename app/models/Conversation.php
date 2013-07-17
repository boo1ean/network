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
}