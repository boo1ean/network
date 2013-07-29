<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Book;
use app\models\Tag;

class AddBookForm extends Book
{

    const TYPE_PAPER = 0;
    const TYPE_EBOOK = 1;

    public function rules() {
        return array(
            array('author, title', 'required'),
        );
    }

    public function scenarios() {
        return array(
            'add' => array('author', 'title', 'description'),
            'edit' => array('author', 'title')
        );
    }

    public function addBook() {
        if ($this->validate()) {

            $book = new Book;

            $book->author = $this->author;
            $book->title = $this->title;
            $book->description = $this->description;
            $book->type = self::TYPE_PAPER;
            $book->status = 'available';
            $book->save();

            $tags_array = $_POST['tags'];

            if ($tags_array !== '') {
                $tags = explode(', ', $tags_array);

                foreach($tags as $tag_title) {
                    if (!Tag::findByTitle($tag_title)) {
                        $tag = new Tag;
                        $tag->title = $tag_title;
                        $tag->save();
                        $book->link('tags', $tag);
                    } else {
                        $tag = Tag::findByTitle($tag_title);
                        $book->link('tags', $tag);
                    }
                }
            }

            return true;
        }

        return false;
    }

    public function saveBook($id) {
        if ($this->validate()) {

            $book = Book::find($id);

            $book->author = $this->author;
            $book->title = $this->title;
            $book->description = $_POST['description'];
            $book->save();
            return true;
        }

        return false;
    }

}