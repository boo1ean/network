<?php

namespace app\models;

use \yii\db\ActiveRecord;

class Event extends ActiveRecord
{
    const TYPE_BIRTHDAY = 0;
    const TYPE_CORPEVENT = 1;
    const TYPE_HOLIDAY = 2;
    const TYPE_DAYOFF = 3;

    public static function tableName() {
        return 'events';
    }

    public static function sortByStartDate() {
        return static::find()
            ->orderBy('start_date')
            ->all();
    }

    /**
     * Set event as read by specified user
     * @param $userId
     */
    public function markAsRead($userId) {
        $query = 'UPDATE `user_events` SET `unread` = 0 WHERE `event_id` = ' . $this->id . ' AND `user_id` = ' . $userId;
        $this->db->createCommand($query)
            ->execute();
    }

    public function markAsUnread($userId) {
        $query = 'UPDATE `user_events` SET `unread` = 1 WHERE `event_id` = ' . $this->id . ' AND `user_id` = ' . $userId;
        $this->db->createCommand($query)
            ->execute();
    }

    public function getUser() {
        return $this->hasOne('User', array('id' => 'user_id'));
    }

    /**
     * @return \yii\db\ActiveRelation object contains events members
     */
    public function getUsers() {
        return $this->hasMany('User', array('id' => 'user_id'))
            ->viaTable('user_events', array('event_id' => 'id'));
    }

    /**
     * @return array of defined types
     */
    public static function getTypes() {
        $types = array();
        $types[] = self::TYPE_BIRTHDAY;
        $types[] = self::TYPE_CORPEVENT;
        $types[] = self::TYPE_DAYOFF;
        $types[] = self::TYPE_HOLIDAY;

        return $types;
    }
}