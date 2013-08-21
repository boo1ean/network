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
use yii\base\InvalidCallException;
use yii\console\Controller;
use app\components\Fixtures;

class FixtureController extends Controller
{
    // Default count of data for generating
    const USERS_COUNT = 10;
    const CONVERSATIONS_COUNT = 20;
    const MESSAGES_COUNT = 50;
    const BOOKS_COUNT = 10;
    const EVENTS_COUNT = 5;

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
     * Creates users, conversations, messages, books, events
     */
    public function actionAll($usersCount = self::USERS_COUNT, $conversationsCount = self::CONVERSATIONS_COUNT,
                              $messagesCount = self::MESSAGES_COUNT, $booksCount = self::BOOKS_COUNT,
                              $eventsCount = self::EVENTS_COUNT) {
        $this->actionUsers($usersCount);
        $this->actionConversations($conversationsCount);
        $this->actionMessages($messagesCount);
        $this->actionBooks($booksCount);
        $this->actionEvents($eventsCount);
    }

    /**
     * Create users
     * @param $usersCount
     */
    public function actionUsers($usersCount = self::USERS_COUNT) {
        $is_admin = $this->fixture->generateUsers($usersCount);
        echo 'Generated ' . $usersCount . ' users ' . ($is_admin ? 'and 1 admin' : '') . PHP_EOL;
    }

    /**
     * Create conversations
     * @param $conversationsCount
     */
    public function actionConversations($conversationsCount = self::CONVERSATIONS_COUNT) {
        try {
            $this->fixture->generateConversations($conversationsCount);
            echo 'Generated ' . $conversationsCount . ' conversations'.PHP_EOL;
        } catch (InvalidCallException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Create messages
     * @param $messagesCount
     */
    public function actionMessages($messagesCount = self::MESSAGES_COUNT) {
        try {
            $this->fixture->generateMessages($messagesCount);
            echo 'Generated ' . $messagesCount . ' messages'.PHP_EOL;
        } catch (InvalidCallException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Create books
     * @param $booksCount
     */
    public function actionBooks($booksCount = self::BOOKS_COUNT) {
        $this->fixture->generateBooks($booksCount);
        echo 'Generated ' . $booksCount . ' books'.PHP_EOL;
    }

    /**
     * Create events
     * @param $eventsCount
     */
    public function actionEvents($eventsCount = self::EVENTS_COUNT) {
        $this->fixture->generateEvents($eventsCount);
        echo 'Generated ' . $eventsCount . ' events'.PHP_EOL;
    }
}