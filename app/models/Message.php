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


class Message extends ActiveRecord
{
    /**
     * @return string name of table in DB
     */
    public static function tableName() {
        return 'messages';
    }

    /**
     * @return \yii\db\ActiveRelation object contains author of the message
     */
    public function getUser() {
        return $this->hasOne('user', array('id' => 'user_id'));
    }
}