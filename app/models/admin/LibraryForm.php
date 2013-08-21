<?php

namespace app\models\admin;

use app\models\Book;
use app\models\Booktaking;
use app\models\Tag;
use Yii;
use yii\base\Model;
use yii\data\Pagination;

class LibraryForm extends Book
{
    /**
     * @var integer book ID to be edited
     */
    public $id_edit;

    /**
     * @var integer limit users on the one page
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

    public  function libraryBookDelete() {
        if ($this->validate()) {
            $book_takings = Booktaking::findByBookId($this->id_edit);

            foreach ($book_takings as $book_taking) {
                $book_taking->delete();
            }

            $book = Book::find($this->id_edit);
            $tags = $book->tags;

            foreach ($tags as $tag) {
                $book->unlink('tags', $tag);
            }

            $book->delete();

            return true;
        } else {
            return false;
        }
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
        $query       = $this->find()->where($where);
        $query_count = clone $query;

        $books = $query->limit($this->limit)
            ->offset(($this->offset - 1) * $this->limit)
            ->orderBy($this->order_by)
            ->all();

        $count_total = $query_count->count();

        if ($this->limit < $count_total) {
            $pagination = new Pagination(array(
                'pageSize'   => $this->limit,
                'totalCount' => $count_total
            ));
        } else {
            $pagination = null;
        }

        return array(
            'pagination' => $pagination,
            'books'      => $books
        );
    }

    /**
     * Save data of book
     * @return boolean
     */
    public function libraryBookSave() {
        if ($this->validate()) {
            $book = empty($this->id_edit) ? new Book() : Book::find($this->id_edit);

            $this->status = empty($this->id_edit) ? parent::STATUS_AVAILABLE : $book->status;
            $tags_old     = $book->tags;

            $book->author      = $this->author;
            $book->description = $this->description;
            $book->status      = $this->status;
            $book->title       = $this->title;
            $book->type        = $this->type;

            if ($book->type == parent::TYPE_ELECTRONIC && !is_null($this->link)) {
                $book->link = $this->link;
            }

            $book->save();
            $this->id = $book->id;

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