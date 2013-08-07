<?php

namespace app\models;

use \yii\db\ActiveRecord;

class Book extends ActiveRecord
{

    public static function tableName() {
        return 'books';
    }

    public static function findByAuthor($author) {
        return static::find()
            ->where(array('author' => $author))
            ->all();
    }

    public static function findByTitle($title) {
        return static::find()
            ->where(array('title' => $title))
            ->one();
    }

    public static function getBooksByParams($status, $param) {
        return static::find()
            ->where(array('status' => $status))
            ->orderBy($param)
            ->all();
    }

    public static function getAllBooks($param) {
        return static::find()
            ->orderBy($param)
            ->all();
    }

    public static function getPaperBooks() {
        return static::find()
            ->where(array('type' => 'paper'))
            ->all();
    }

    public static function getEbooks() {
        return static::find()
            ->where(array('type' => 'ebook'))
            ->all();
    }

    public function getTags() {
        return $this->hasMany('Tag', array('id' => 'tag_id'))
            ->viaTable('book_tags', array('book_id' => 'id'));
    }

}