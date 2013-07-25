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

    public static function findByTag($tag) {
        return static::find()
            ->where(array('tags' => $tag))
            ->all();
    }

    public static function getAvailableBooks() {
        return static::find()
            ->where(array('status' => 'available'))
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

}