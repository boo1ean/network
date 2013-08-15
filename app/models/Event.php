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

    public static function findByTitleAndDate($title, $start_date, $end_date) {
        return static::find()
            ->where(array('title' => $title,
                          'start_date' => $start_date,
                          'end_date' => $end_date
            ))
            ->one();
    }

    public function getUser() {
        return $this->hasOne('User', array('id' => 'user_id'));
    }
}