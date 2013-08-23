<?php

namespace app\models;
use \yii\db\ActiveRecord;

class Resource extends ActiveRecord{

    public static function tableName() {
        return 'resources';
    }

}