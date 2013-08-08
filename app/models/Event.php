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

    public static function findByTitle($title) {
        return static::find()
            ->where(array('title' => $title))
            ->one();
    }

    public function getUsers() {
        return $this->hasMany('User', array('id' => 'user_id'))
            ->viaTable('user_events', array('event_id' => 'id'));
    }

}