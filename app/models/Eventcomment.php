<?php

namespace app\models;

use \yii\db\ActiveRecord;

class Eventcomment extends ActiveRecord
{

    public static function tableName() {
        return 'event_comments';
    }

    public static function byEvent($id) {
        return static::find()
            ->where(array('event_id' => $id))
            ->orderBy('post_datetime desc')
            ->all();
    }
}