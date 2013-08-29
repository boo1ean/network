<?php

namespace app\controllers;

use app\models\Book;
use app\models\BookTaking;
use app\models\Tag;
use yii;
use yii\data\Pagination;

class LibraryController extends PjaxController
{
    public function actionAskForBook() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->getBaseUrl(),
            'status'   => 'ok'
        );

        if (!isset($_POST['book_id'])) {
            $result['status'] = 'redirect';
        }

        if ('ok' == $result['status']) {
            $bookTaking = new BookTaking();

            $bookTaking->book_id = $_POST['book_id'];
            $bookTaking->scenario = 'ask';

            $result['status'] = $bookTaking->addToAskOrder() ? 'ok' : 'error';
            $result['errors'] = $bookTaking->errors;
        }

        return json_encode($result);
    }

    public function actionBooks($status = 'all', $order = 'author-asc', $page = 1, $tags = '') {
        $bookModel = new Book();
        $storage   = Yii::$app->getComponent('storage');
        $all_tags  = Tag::getTags();
        $user      = Yii::$app->getUser()->getIdentity();
        $where     = array();

        switch($status) {
            case 'ask':
                $where['status'] = Book::STATUS_ASK;
                break;
            case 'available':
                $where['status'] = Book::STATUS_AVAILABLE;
                break;
            case 'taken':
                $where['status'] = Book::STATUS_TAKEN;
                break;
        }

        $bookModel->offset   = $page;
        $bookModel->order_by = str_replace('-', ' ', $order);

        $books_data = $bookModel->getBookList($where, '' == $tags);
        $books      = array();
        $where      = array('user_id' => $user->id);

        if('' != $tags) {
            $tags = strstr($tags, '/') === false ? array($tags) : explode('/', $tags);
            $books_tmp = array();

            foreach ($books_data['books'] as $book) {

                if(count($book->tags) < count($tags)) {
                    continue;
                }

                $match = 0;
                foreach ($tags as $tag) {
                    foreach ($book->tags as $tag_current) {

                        if($tag == $tag_current->title) {
                            $match++;
                        }

                    }
                }

                if($match == count($tags)) {
                    $books_tmp[] = $book;
                }
            }
            $books_data['count_total'] = count($books_tmp);
            $books_data['books']       = $books_tmp;
        }

        foreach ($books_data['books'] as $key => $book) {

            if(is_array($tags)) {
                if($key < ($bookModel->offset - 1) * $bookModel->limit) {
                    continue;
                }

                if(count($books) >= $bookModel->limit) {
                    break;
                }
            }

            $books[$key]         = $book->toArray();
            $books[$key]['tags'] = $book->tags;
            $where['book_id']    = $book->id;

            switch ($books[$key]['status']) {
                case Book::STATUS_ASK:
                    $books[$key]['status']     = 'ask';
                    $where['status_user_book'] = BookTaking::STATUS_ASK;
                    $book_taking               = BookTaking::findOneByParams($where);
                    $books[$key]['show_ask']   = !is_object($book_taking);
                    break;
                case Book::STATUS_AVAILABLE:
                    $books[$key]['status']   = 'available';
                    $books[$key]['show_ask'] = true;
                    break;
                case Book::STATUS_TAKEN:
                    $books[$key]['status']     = 'taken';
                    $where['status_user_book'] = BookTaking::STATUS_TAKEN;
                    $book_taking               = BookTaking::findOneByParams($where);

                    $books[$key]['percent'] = BookTaking::calcPercentFromDateInterval($book_taking['taken'], $book_taking['returned']);

                    if ($books[$key]['percent'] <= 50) {
                        $books[$key]['class'] = 'success';
                    } elseif ($books[$key]['percent'] > 80) {
                        $books[$key]['class'] = 'danger';
                    } else {
                        $books[$key]['class'] = 'warning';
                    }

                    $books[$key]['show_ask']   = is_object($book_taking);
                    break;
            }

            if ($books[$key]['type'] == Book::TYPE_PAPER) {
                $books[$key]['type'] = 'Paper';
            } else {
                $books[$key]['type'] = 'E-book';
                $books[$key]['link'] = $storage->link($book->resource_id);
            }

        }

        if (isset($books_data['count_total']) && $bookModel->limit < $books_data['count_total']) {
            $pagination = new Pagination(array(
                'pageSize'   => $bookModel->limit,
                'totalCount' => $books_data['count_total']
            ));
        } else {
            $pagination = null;

            if($page != 1) {
                Yii::$app->getResponse()->redirect('/library/books/'.$status.'/'.$order.'/1/'.implode('/', $tags));
            }

        }

        $param = array(
            'books'       => $books,
            'order'       => $order,
            'page'        => $page,
            'pagination'  => $pagination,
            'status'      => $status,
            'tags'        => $all_tags,
            'tags_filter' => $tags
        );

        return $this->render('bookList', $param);
    }
}
