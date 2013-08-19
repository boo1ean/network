<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Book;
use app\models\Tag;
use yii\web\UploadedFile;

class AddBookForm extends Book
{
    public function rules() {
        return array(
            array('author, title, description', 'required'),
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

            if ($_FILES['ebook']['name'] !== '' && !empty($_FILES['ebook']['tmp_name'])) {
                $book_file = UploadedFile::getInstanceByName('ebook');
                $storage = Yii::$app->getComponent('storage');
                $link = $storage->save($book_file);

                $book->type = parent::TYPE_ELECTRONIC;
                $book->link = $link;
            } else {
                $book->type = parent::TYPE_PAPER;
            }

            $book->status = 'available';
            $book->save();

            $tags_array = $_POST['tags'];

            if ($tags_array !== '') {
                $tags = explode(',', $tags_array);

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

            if ($book->link == null && $_FILES['ebook']['name'] !== '' && !empty($_FILES['ebook']['tmp_name'])) {
                $book_file = UploadedFile::getInstanceByName('ebook');
                $storage = Yii::$app->getComponent('storage');
                $link = $storage->save($book_file);

                $book->type = parent::TYPE_ELECTRONIC;
                $book->link = $link;
            }

            $book->save();

            //massive of new tags
            $tags_array = $_POST['tags'];

            //massive of old tags for checking if tag was removed on edit
            $books = Book::findByTitle($this->title);
            $tags_before_edit = $books->tags;

            if ($tags_array !== '') {
                $tags = explode(',', $tags_array);

                //checking delete tags while edit book
                //tags before edit
                foreach($tags_before_edit as $tag)  {
                    //new tags
                    foreach($tags as $new_tag) {
                        //if tag before edit equals any new tag - then user didn't delete it
                        if ($tag->title == $new_tag) {
                            $tag_not_deleted = true;
                            break;
                        } else {
                            $tag_not_deleted = false;
                        }
                    }

                    if (!$tag_not_deleted) {
                        $book->unlink('tags', $tag);
                    }
                }

                //new tags
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

}