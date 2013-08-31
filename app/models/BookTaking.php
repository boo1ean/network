<?php

namespace app\models;

use \app\models\Book;
use \yii;
use \yii\db\ActiveRecord;

class BookTaking extends ActiveRecord
{
    // Status of the book relative to the user
    const STATUS_RETURNED = 1;
    const STATUS_TAKEN    = 2;
    const STATUS_ASK      = 3;

    public static function tableName() {
        return 'book_taking';
    }

    /**
     * @return scenarios array
     */
    public function scenarios() {
        return array(
            'ask'     => array('book_id'),
            'default' => array('book_id'),
            'give'    => array('book_id', 'returned', 'taken', 'user_id'),
            'return'  => array('book_id', 'user_id')
        );
    }

    /**
     * @return validation rules array
     */
    public function rules() {
        return array(
            array('book_id, returned, taken, user_id', 'required'),
            array('book_id', 'validAskId'),
            array('returned', 'validReturnedDate')
        );
    }

    public function addToAskOrder() {
        if ($this->validate()) {
            $book = Book::find($this->book_id);

            $book->status = Book::STATUS_ASK;
            $book->save();

            $this->user_id           = Yii::$app->getUser()->getIdentity()->id;
            $this->status_user_book  = self::STATUS_ASK;

            $this->save();
            return true;
        } else {
            return false;
        }
    }

    public static function calcPercentFromDateInterval($start, $end, $round = 1) {
        $now      = time();
        $datetime = explode(' ', $end);
        $date     = explode('-', $datetime[0]);
        $time     = isset($datetime[1]) ? explode(':', $datetime[1]) : array(0,0,0);
        $returned = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);

        $datetime = explode(' ', $start);
        $date     = explode('-', $datetime[0]);
        $time     = isset($datetime[1]) ? explode(':', $datetime[1]) : array(0,0,0);
        $taken    = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);

        return round(($now - $taken) * 100 / ($returned - $taken), $round);
    }

    public static function findByBookId($book_id) {
        return static::find()
            ->where(array('book_id' => $book_id))
            ->all();
    }

    public static function findOneByParams($where = array()) {
        return static::find()
            ->where($where)
            ->one();
    }

    public function getQueueListOfUsers () {
        if ($this->validate()) {
            $result = User::createQuery()
                ->select('users.*')
                ->from('users')
                ->join('inner join', 'book_taking', 'book_taking.user_id = users.id')
                ->join('inner join', 'books',       'books.id = book_taking.book_id')
                ->where('book_taking.status_user_book = ' . self::STATUS_ASK)
                ->andWhere('book_taking.book_id = ' . $this->book_id)
                ->all();

            return $result;
        } else {
            return false;
        }
    }

    public function getUser() {
        return $this->hasOne('User', array('id' => 'user_id'));
    }

    public function getBook() {
        return $this->hasOne('Book', array('id' => 'book_id'));
    }

    public function giveBook() {
        if ($this->validate()) {
            $book = Book::find($this->book_id);

            $book->status = Book::STATUS_TAKEN;
            $book->save();

            $bookTaking = $this->findOneByParams(array(
                'book_id'          => $this->book_id,
                'status_user_book' => self::STATUS_ASK,
                'user_id'          => $this->user_id
            ));

            $date_time = explode(' ', $this->returned);
            $date = explode('/', $date_time[0]);

            $bookTaking->returned         = $date[2] . '-' . $date[1] . '-' . $date[0] . ' ' . $date_time[1];
            $bookTaking->status_user_book = self::STATUS_TAKEN;
            $bookTaking->taken            = $this->taken;
            $bookTaking->save();
            return true;
        } else {
            return false;
        }
    }

    public function returnBook() {
        if ($this->validate()) {

            $is_queue = $this->findOneByParams(array(
                'book_id'          => $this->book_id,
                'status_user_book' => self::STATUS_ASK
            ));

            $bookTaking = $this->findOneByParams(array(
                'book_id'          => $this->book_id,
                'status_user_book' => self::STATUS_TAKEN,
                'user_id'          => $this->user_id
            ));

            $book = Book::find($this->book_id);

            $book->status = is_object($is_queue) ? Book::STATUS_ASK : Book::STATUS_AVAILABLE;
            $book->save();

            $this->status_user_book = $book->status;

            $bookTaking->returned         = date('Y-m-d H:i:s', time());
            $bookTaking->status_user_book = self::STATUS_RETURNED;
            $bookTaking->save();
            return true;
        } else {
            return false;
        }
    }

    public function validAskId() {
        $where = array(
            'book_id'          => $this->book_id,
            'user_id'          => Yii::$app->getUser()->getIdentity()->id,
            'status_user_book' => self::STATUS_ASK
        );
        $user = $this->findOneByParams($where);

        if ($user && 'ask' == $this->scenario) {
            $this->addError('book_id', 'You already in the order');
        }
    }

    public function validReturnedDate() {
        $datetime = explode(' ', $this->returned);
        $date     = explode('/', $datetime[0]);
        $time     = explode(':', $datetime[1]);
        $returned = mktime($time[0], $time[1], 0, $date[1], $date[0], $date[2]);

        if($returned < time()) {
            $this->addError('returned', 'End date can\'t be in past');
        }
    }
}