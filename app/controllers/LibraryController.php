<?php

namespace app\controllers;

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
            'books' => $books,
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

}
