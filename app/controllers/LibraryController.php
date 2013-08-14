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
    const STATUS_TAKEN = 1;
    const STATUS_UNTAKEN = 2;

    public function actionBooks() {

        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        if (isset($_POST['id_status']) && isset($_POST['id_param']) && $_POST['id_status'] != 'all') {
            $books = Book::getBooksByParams($_POST['id_status'], $_POST['id_param']);
        } else if (isset($_POST['id_status']) && isset($_POST['id_param']) && $_POST['id_status'] == 'all') {
            $books = Book::getAllBooks($_POST['id_param']);
        } else {
            $books = Book::getAllBooks(null);
        }

        if(isset($_POST['sel_tags'])) {
            $selected_tags = $_POST['sel_tags'];
            $books = array();

            foreach($selected_tags as $tag) {
                $tag_curr = Tag::findByTitle($tag);
                $books_by_tag = $tag_curr->books;
                $books = array_merge($books, $books_by_tag);
            }
        }

        $all_tags = Tag::getTags();

        if (isset($_POST['partial']) && $_POST['partial'] == 'yes') {
            $this->layout = 'block';

            return $this->renderPartial('bookslist', array(
                'books' => $books,
                'all_tags' => $all_tags
            ));
        } else {
            return $this->render('books', array(
                'books' => $books,
                'all_tags' => $all_tags
            ));
        }
    }

    public function actionAddbook() {

        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $bookForm = new AddBookForm();

        $bookForm->scenario = 'add';

        if ($bookForm->load($_POST) && $bookForm->addBook()) {
            Yii::$app->getResponse()->redirect('@web/library/books');
        } else {
            return $this->render('addbook', array(
                'model' => $bookForm,
            ));
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

    public function actionDeletebook() {

        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $booktakings = Booktaking::findByBookId($_POST['id']);

        foreach ($booktakings as $booktaking) {
            $booktaking->delete();
        }

        $book = Book::find($_POST['id']);

        $tags = $book->tags;

        foreach ($tags as $tag) {
            $book->unlink('tags', $tag);
        }

        $book->delete();

        return Yii::$app->getResponse()->redirect('@web/library/books');
    }

    public function actionTakebook() {

        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $book = Book::find($_POST['id']);

        $book_take = new Booktaking;
        $book_take->book_id = $_POST['id'];
        $book_take->user_id = Yii::$app->getUser()->getIdentity()->id;
        $book_take->taken = date('Y-m-d');
        $tomorrow  = mktime(0, 0, 0, date("m"), date("d")+1, date("Y"));
        $book_take->returned = date('Y-m-d', $tomorrow);
        $book_take->save();

        $book->status = 'taken';
        $book->save();

        return Yii::$app->getResponse()->redirect('@web/library/books');
    }

    public function actionUntakebook() {

        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $book = Book::find($_POST['id']);

        $book_take = Booktaking::findByBookIdAndStatus($_POST['id'], self::STATUS_TAKEN);
        $book_take->returned = date('Y-m-d');
        $book_take->status = self::STATUS_UNTAKEN;
        $book_take->save();

        $book->status = 'available';
        $book->save();

        return Yii::$app->getResponse()->redirect('@web/library/books');
    }

}
