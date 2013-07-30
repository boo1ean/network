<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sophie
 * Date: 22.07.13
 * Time: 0:05
 * To change this template use File | Settings | File Templates.
 */

namespace app\controllers;

use yii\base\Exception;
use yii\console\Controller;
use app\components\Fixtures;

class FixtureController extends Controller
{
    // Default count of data for generating
    const USERS_COUNT = 10;
    const CONVERSATIONS_COUNT = 20;
    const MESSAGES_COUNT = 50;
    const BOOKS_COUNT = 20;

    /**
     * @var object fixtures
     */
    private $fixture;

    /**
     * @var string the default command action.
     */
    public $defaultAction = 'all';

    /**
     * Create fixture object
     * @return void
     */
    public function init() {
        $this->fixture = new Fixtures();
    }

    /**
     * Creates users, conversations and members
     */
    public function actionAll($usersCount = self::USERS_COUNT, $conversationsCount = self::CONVERSATIONS_COUNT,
                              $messagesCount = self::MESSAGES_COUNT, $booksCount = self::BOOKS_COUNT) {
        $this->actionUsers($usersCount);
        $this->actionConversations($conversationsCount);
        $this->actionMessages($messagesCount);
        $this->actionBooks($booksCount);
    }

    /**
     * Create users
     * @param $usersCount
     */
    public function actionUsers($usersCount = self::USERS_COUNT) {
        $this->fixture->generateUsers($usersCount);
        echo 'Generated ' . $usersCount . " users \n" ;
    }

    /**
     * Create conversations
     * @param $conversationsCount
     */
    public function actionConversations($conversationsCount = self::CONVERSATIONS_COUNT) {
        $this->fixture->generateConversations($conversationsCount);
        echo 'Generated ' . $conversationsCount . " conversations \n";
    }

    /**
     * Create messages
     * @param $messagesCount
     */
    public function actionMessages($messagesCount = self::MESSAGES_COUNT) {
        $this->fixture->generateMessages($messagesCount);
        echo 'Generated ' . $messagesCount . " messages \n";
    }

    /**
     * Create books
     * @param $booksCount
     */
    public function actionBooks($booksCount = self::BOOKS_COUNT) {
        $this->fixture->generateBooks($booksCount);
        echo 'Generated ' . $booksCount . " books \n";
    }
}