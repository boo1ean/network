<?php

namespace app\models;

use \yii\db\ActiveRecord;

class Book extends ActiveRecord
{
    /**
     * @var integer limit books on the one page
     */
    public $limit = 10;

    /**
     * @var integer what is the recording start
     */
    public $offset = 1;

    /**
     * @var string
     */
    public $order_by = 'author asc';

    // Book types
    const TYPE_PAPER      = 1;
    const TYPE_ELECTRONIC = 2;

    // Book status
    const STATUS_AVAILABLE = 1;
    const STATUS_TAKEN     = 2;
    const STATUS_ASK       = 3;

    public static function tableName() {
        return 'books';
    }

    public function getBookList ($where = array(), $with_count = false) {
        $query  = $this->find()->where($where);
        $result = array();

        if($with_count) {
            $query_count           = clone $query;
            $result['count_total'] = $query_count->count();
        }

        $result['books'] = $query->limit($this->limit)
            ->offset(($this->offset - 1) * $this->limit)
            ->orderBy($this->order_by)
            ->all();
        return $result;
    }

    public function getTags() {
        return $this->hasMany('Tag', array('id' => 'tag_id'))
            ->viaTable('book_tags', array('book_id' => 'id'));
    }

}