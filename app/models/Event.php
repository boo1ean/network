<?php

namespace app\models;

use \yii\db\ActiveRecord;

class Event extends ActiveRecord
{

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

    public function getUser() {
        return $this->hasOne('User', array('id' => 'user_id'));
    }
}