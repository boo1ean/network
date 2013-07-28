<?php

namespace app\models;

use \yii\db\ActiveRecord;

class Booktaking extends ActiveRecord
{

    public static function tableName() {
        return 'book_taking';
    }

    public static function findByBookId($book_id) {
        return static::find()
            ->where(array('book_id' => $book_id))
            ->all();
    }

}