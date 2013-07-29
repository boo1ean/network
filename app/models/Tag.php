<?php

namespace app\models;

use \yii\db\ActiveRecord;

class Tag extends ActiveRecord
{

    public static function tableName() {
        return 'tags';
    }

    public static function findByTitle($title) {
        return static::find()
            ->where(array('title' => $title))
            ->one();
    }

    public static function getTags() {
        return static::find()
            ->all();
    }

    public function getBooks() {
        return $this->hasMany('Book', array('id' => 'book_id'))
            ->viaTable('book_tags', array('tag_id' => 'id'))
            ->all();
    }

}