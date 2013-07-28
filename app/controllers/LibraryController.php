<?php

namespace app\controllers;

use app\models\Booktaking;
use app\models\EditBookForm;
use yii;
use yii\web\Controller;
use app\models\Book;
use app\models\AddBookForm;

class LibraryController extends Controller
{

    public function actionBooks($id = null) {

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
                $books = Book::getAvailableBooks();
                break;
        }

        return $this->render('books', array(
            'books' => $books
        ));
    }

    public function actionAddbook() {

        $bookForm = new AddBookForm();

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

        $book = Book::find($id);

        $bookForm = new AddBookForm;

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

        $booktaking = Booktaking::findByBookId($id);
        $booktaking->delete();

        $book = Book::find($id);
        $book->delete();

        return Yii::$app->getResponse()->redirect('@web/library/books');
    }

    public function actionTakebook($id = null) {

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
