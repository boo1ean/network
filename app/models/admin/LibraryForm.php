<?php

namespace app\models\admin;

use Yii;
use yii\base\Model;
use app\models\Book;
use app\models\Tag;

class LibraryForm extends Book
{
    /**
     * @var integer book ID to be edited
     */
    public $id_edit;

    /**
     * @var string
     */
    public $order_by = 'author asc';

    /**
     * @var string tags of book
     */
    public $tags;

    /**
     * @return validation rules array
     */
    public function rules() {
        return array(
            array('author, description, id_edit, title, type', 'required')
        );
    }

    /**
     * @return scenarios array
     */
    public function scenarios() {
        return array(
            'default' => array('author', 'description', 'id_edit', 'title', 'type'),
            'only_id' => array('id_edit')
        );
    }

    /**
     * Load data for edit book
     * @return boolean
     */
    public function libraryBookEdit() {
        if ($this->validate()) {
            $book = Book::find($this->id_edit);

            $this->author      = $book->author;
            $this->description = $book->description;
            $this->link        = $book->link;
            $this->title       = $book->title;
            $this->type        = $book->type;
            $this->tags        = $book->tags;
            return true;
        } else {
            return false;
        }
    }

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

    /**
     * Save data of book
     * @return boolean
     */
    public function libraryBookSave() {
        if ($this->validate()) {
            $book     = Book::find($this->id_edit);
            $tags_old = $book->tags;

            $book->author      = $this->author;
            $book->description = $this->description;
            $book->title       = $this->title;
            $book->type        = $this->type;

            if ($book->type == parent::TYPE_ELECTRONIC && '' !== $this->link) {
                $book->link = $this->link;
            }

            $book->save();

            if('' !== $this->tags) {
                $tags_new = explode(',', $this->tags);
                foreach ($tags_old as $old) {
                    foreach ($tags_new as $key => $new) {
                        if ($old->title != $new && end($tags_new) == $new) {
                            $book->unlink('tags', $old);
                        }
                    }
                }

                foreach ($tags_new as $new) {
                    $tag = Tag::findByTitle($new);

                    if (!$tag) {
                        $tag = new Tag;
                        $tag->title = $new;
                        $tag->save();

                    }

                    $book->link('tags', $tag);
                }
            }

            return true;
        } else {
            return false;
        }
    }
}