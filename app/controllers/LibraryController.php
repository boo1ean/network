<?php

namespace app\controllers;

use app\models\Booktaking;
use app\models\EditBookForm;
use yii;
use yii\web\Controller;
use app\models\Book;
use app\models\Tag;
use app\models\AddBookForm;

class LibraryController extends Controller
{

    public function actionBooks($id = null) {

        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        switch($id) {
            case 'bytitle':
                $books = Book::sortByTitle();
                break;
            case 'byauthor':
                $books = Book::sortByAuthor();
                break;
            case 'available':
                $books = Book::getAvailableBooks();
                break;
            case 'taken':
                $books = Book::getTakenBooks();
                break;
            default:
                if (isset($id)) {
                    $tags = Tag::find($id);
                    $books = $tags->books;
                } else {
                    $books = Book::getAvailableBooks();
                }

                break;
        }

        return $this->render('books', array(
            'books' => $books
        ));
    }

    public function actionAddbook() {

        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $bookForm = new AddBookForm();

        $bookForm->scenario = 'add';

        if ($bookForm->load($_POST) && $bookForm->addBook()) {
            return $this->render('addbook', array(
                'model'   => $bookForm,
                'message' => 'Well done! You successfully added new book.'
            ));
        } else {
            return $this->render('addbook', array('model' => $bookForm));
        }
    }

    public function actionEditbook($id = null) {

        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $book = Book::find($id);

        $bookForm = new AddBookForm;

        $bookForm->scenario = 'edit';

        if ($bookForm->load($_POST) && $bookForm->saveBook($id)) {

            $book = Book::find($id);

            return $this->render('editbook', array(
                'model'   => $bookForm,
                'message' => 'Well done! You successfully edit book.',
                'book' => $book
            ));
        } else {
            return $this->render('editbook', array(
                'model'   => $bookForm,
                'book' => $book
            ));
        }

    }

    public function actionDeletebook($id = null) {

        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $booktaking = Booktaking::findByBookId($id);

        if ($booktaking) {
            $booktaking->delete();
        }

        $book = Book::find($id);
        $book->delete();

        return Yii::$app->getResponse()->redirect('@web/library/books');
    }

    public function actionTakebook($id = null) {

        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $book = Book::find($id);

        $book_take = new Booktaking;

        $book_take->book_id = $id;
        $book_take->user_id = Yii::$app->getUser()->getIdentity()->id;
        $book_take->taken = date('Y-m-d');

        $tomorrow  = mktime(0, 0, 0, date("m"), date("d")+1, date("Y"));

        $book_take->returned = date('Y-m-d', $tomorrow);

        $book_take->save();

        $book->status = 'taken';

        $book->save();

        return Yii::$app->getResponse()->redirect('@web/library/books');
    }

}
