<?php

namespace app\models;

use \yii\db\ActiveRecord;

class Userevent extends ActiveRecord
{

    public static function tableName() {
        return 'user_events';
    }

    public static function findByEventId($event_id) {
        return static::find()
            ->where(array('event_id' => $event_id))
            ->all();
    }

    public static function findByUserId($user_id) {
        return static::find()
            ->where(array('user_id' => $user_id))
            ->all();
    }

    public function getEvent() {
        return $this->hasOne('Event', array('id' => 'event_id'));
    }

    public function getUser() {
        return $this->hasOne('User', array('id' => 'user_id'));
    }

}