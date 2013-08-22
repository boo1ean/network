<?php

namespace app\models;

use \yii\db\ActiveRecord;

class BookTaking extends ActiveRecord
{

    public static function tableName() {
        return 'book_taking';
    }

    public static function findByBookId($book_id) {
        return static::find()
            ->where(array('book_id' => $book_id))
            ->all();
    }

    public static function findByBookIdAndStatus($book_id, $status) {
        return static::find()
            ->where(array('book_id' => $book_id, 'status' => $status))
            ->one();
    }

    public function getUser() {
        return $this->hasOne('User', array('id' => 'user_id'));
    }

    public function getBook() {
        return $this->hasOne('Book', array('id' => 'book_id'));
    }

}