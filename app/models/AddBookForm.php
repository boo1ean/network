<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Book;

class AddBookForm extends Book
{

    public function rules() {
        return array(
            array('author, title, description', 'required'),
        );
    }

    public function addBook() {
        if ($this->validate()) {

            $book = new Book;

            $book->author = $this->author;
            $book->title = $this->title;
            $book->description = $this->description;
            $book->type = 'paper';
            $book->status = 'available';
            $book->save();
            return true;
        }

        return false;
    }

    public function saveBook($id) {
        if ($this->validate()) {

            $book = Book::find($id);

            $book->author = $this->author;
            $book->title = $this->title;
            $book->description = $this->description;
            $book->type = 'paper';
            $book->status = 'available';
            $book->save();
            return true;
        }

        return false;
    }

}