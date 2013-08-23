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
    public $id_ask;

    // Status of the book relative to the user
    const STATUS_RETURNED = 1;
    const STATUS_TAKEN    = 2;
    const STATUS_ASK      = 3;

    public static function tableName() {
        return 'book_taking';
    }

    /**
     * @return validation rules array
     */
    public function rules() {
        return array(
            array('id_ask', 'required'),
            array('id_ask', 'validAskId')
        );
    }

    public function addToAskOrder() {
        if ($this->validate()) {
            $book = Book::find($this->id_ask);

            $book->status = Book::STATUS_ASK;
            $book->save();

            $this->book_id           = $this->id_ask;
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

    public static function findByBookIdAndStatus($book_id, $status) {
        return static::find()
            ->where(array('book_id' => $book_id, 'status_user_book' => $status))
            ->one();
    }

    public static function findByUserIdAndStatus($user_id, $status) {
        return static::find()
            ->where(array('user_id' => $user_id, 'status_user_book' => $status))
            ->one();
    }

    public function getUser() {
        return $this->hasOne('User', array('id' => 'user_id'));
    }

    public function getBook() {
        return $this->hasOne('Book', array('id' => 'book_id'));
    }

    public function validAskId() {
        $user = $this->findByBookIdAndStatus($this->id_ask, self::STATUS_ASK);

        if ($user) {
            $this->addError('id_ask', 'You already in the order');
        }
    }
}