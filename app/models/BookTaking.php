<?php

namespace app\models;

use \app\models\Book;
use \yii;
use \yii\db\ActiveRecord;

class BookTaking extends ActiveRecord
{
    /**
     * @var integer book ID
     */
    public $id_book;

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
            'ask'     => array('id_book'),
            'default' => array('id_book')
        );
    }

    /**
     * @return validation rules array
     */
    public function rules() {
        return array(
            array('id_book', 'required'),
            array('id_book', 'validAskId')
        );
    }

    public function addToAskOrder() {
        if ($this->validate()) {
            $book = Book::find($this->id_book);

            $book->status = Book::STATUS_ASK;
            $book->save();

            $this->book_id           = $this->id_book;
            $this->user_id           = Yii::$app->getUser()->getIdentity()->id;
            $this->status_user_book  = self::STATUS_ASK;

            $this->save();
            return true;
        } else {
            return false;
        }
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
                ->andWhere('book_taking.book_id = ' . $this->id_book)
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

    public function validAskId() {
        $where = array(
            'book_id'          => $this->id_book,
            'user_id'          => Yii::$app->getUser()->getIdentity()->id,
            'status_user_book' => self::STATUS_ASK
        );
        $user = $this->findOneByParams($where);

        if ($user && 'ask' == $this->scenario) {
            $this->addError('id_book', 'You already in the order');
        }
    }
}