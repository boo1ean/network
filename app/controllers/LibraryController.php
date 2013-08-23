<?php

namespace app\controllers;

use app\models\Book;
use app\models\BookTaking;
use app\models\Tag;
use yii;
use yii\data\Pagination;

class LibraryController extends PjaxController
{
    public function beforeAction($action) {
        // Check user on access
        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('/');
            return false;
        }

        return parent::beforeAction($action);
    }

    public function actionBooks($status = 'all', $order = 'author-asc', $page = 1) {
        $bookModel = new Book();
        $tagModel  = new Tag();
        $where     = array();

        switch($status) {
            case 'available':
                $where['status'] = Book::STATUS_AVAILABLE;
                break;
            case 'taken':
                $where['status'] = Book::STATUS_TAKEN;
                break;
        }

        $bookModel->offset   = $page;
        $bookModel->order_by = str_replace('-', ' ', $order);
        $books_data = $bookModel->getBookList($where, true);
        $books      = array();

        foreach ($books_data['books'] as $key => $book) {
            $books[$key]           = $book;
            $books[$key]['status'] = $books[$key]['status'] == Book::STATUS_AVAILABLE ? 'available' : 'taken';
            $books[$key]['type']   = $books[$key]['type']   == Book::TYPE_PAPER       ? 'Paper'     : 'E-book';
        }

        $tags = $tagModel->getTags();

        if (isset($books_data['count_total']) && $bookModel->limit < $books_data['count_total']) {
            $pagination = new Pagination(array(
                'pageSize'   => $bookModel->limit,
                'totalCount' => $books_data['count_total']
            ));
        } else {
            $pagination = null;
        }

        $param = array(
            'books'      => $books,
            'order'      => $order,
            'page'       => $page,
            'pagination' => $pagination,
            'status'     => $status,
            'tags'       => $tags
        );

        return $this->render('bookList', $param);
    }

    public function actionTakebook() {

        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $book = Book::find($_POST['id']);

        $book_take = new BookTaking;
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

        $book_take = BookTaking::findByBookIdAndStatus($_POST['id'], 1);
        $book_take->returned = date('Y-m-d');
        $book_take->status = 2;
        $book_take->save();

        $book->status = Book::STATUS_AVAILABLE;
        $book->save();

        return Yii::$app->getResponse()->redirect('@web/library/books');
    }

}
