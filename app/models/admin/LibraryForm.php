<?php

namespace app\models\admin;

use Yii;
use yii\base\Model;
use yii\data\Pagination;
use app\models\Book;

class LibraryForm extends Book
{
    /**
     * @var string
     */
    public $order_by = 'author asc';

    /**
     * List of books
     * @return array
     */
    public function libraryBookList($where = array()) {
        return $query = $this->find()
            ->where($where)
            ->orderBy($this->order_by)
            ->all();
    }
}