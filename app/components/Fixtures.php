<?php
namespace app\components;

use yii\base\Component;
use yii\base\InvalidCallException;
use yii;
use app\models\User;
use \app\models\Conversation;
use \app\models\Message;
use \app\models\Book;
use \app\models\Tag;
use \app\models\Event;

class Fixtures extends Component
{
    /**
     * @var \Faker\Generator
     */
    private $faker;
    
    function __construct() {
        $this->faker = \Faker\Factory::create();
    }
    
    /**
     * Generates single user
     */
    public function generateUser($user_type = User::TYPE_USER) {
        $fakeUser = new User;

        $fakeUser->email      = $user_type == User::TYPE_USER ? $this->faker->email : 'admin@gmail.com';
        $fakeUser->first_name = $this->faker->firstName;
        $fakeUser->is_active  = 1;
        $fakeUser->last_name  = $this->faker->lastName;
        $fakeUser->password   = $user_type == User::TYPE_USER ? '123' : 'admin';
        $fakeUser->type       = $user_type;
        $fakeUser->save();
    }

    /**
     * Generates users
     * @param integer $number number of users to generate
     */
    public function generateUsers($number) {
        for( $i = 0; $i < $number; $i++) {
            $this->generateUser();
        }

        $count_admins = User::find()->where(array('type' => User::TYPE_ADMIN))->count();
        if(0 == $count_admins) {
            $this->generateUser(User::TYPE_ADMIN);
            return true;
        }

        return false;
    }
        
     /**
     * Generates conversation
     */
    public function generateConversation() {  

        $allUsers = User::find()->all();
        $usersCount = count($allUsers);
        if($usersCount <= 2) {
            throw new InvalidCallException('Not enough users to generate conversations!');
        }
        // Create conversation
        $fakeConversation = new Conversation;

        $fakeConversation->title = $this->faker->word . 'Conversation';
        $fakeConversation->save();

        // Random number of conversation members
        $maxRand = min($usersCount - 1, 5);
        $conversationsUsersCount = rand(2, $maxRand);

        // Shuffle allUsers array to get random users
        shuffle($allUsers);
        for ($i = 0; $i < $conversationsUsersCount; $i++) {
            if (0 == $i) {
                $fakeConversation->creator = $allUsers[$i]->id;
                $fakeConversation->save();
            }
            $fakeConversation->addSubscribed($allUsers[$i]);
        }
    }

    /**
     * Generates conversations
     * @param integer $number number of conversations to generate
     */
    public function generateConversations($number) {
        for( $i = 0; $i < $number; $i++) {
            $this->generateConversation();
        }
    }
    
    /**
     * Generates message
     */
    public function generateMessage() {

        $fakeMessage = new Message;
        $fakeConversation = new Conversation;

        // Get conversations count
        $conversationsCount = Conversation::find()->count();
        if ($conversationsCount <= 0) {
            throw new InvalidCallException('There are no conversations!');
        }
        // Generate random offset
        $randConversationOffset = rand(0, $conversationsCount - 1);

        // Get conversation with random offset
        $fakeConversation = Conversation::find()
            ->limit(1)
            ->offset($randConversationOffset)
            ->one();

        // Fake message belongs to fake conversation
        $fakeMessage->conversation_id = $fakeConversation->id;

        // Get random user from conversation members
        $fakeConversationUsersCount = count($fakeConversation->users);
        if ($fakeConversationUsersCount <= 0) {
            throw new InvalidCallException('There are no members of conversation!');
        }
        $fakeUserNumber = rand(0, $fakeConversationUsersCount - 1);
        $fakeUser = $fakeConversation->users[$fakeUserNumber];

        // Set message user
        $fakeMessage->user_id = $fakeUser->id;

        // Set message body
        $fakeMessage->body = $this->faker->text;

        // Save message
        $fakeMessage->save();
    }
    
    /**
     * Generates messages
     * @param integer $number number of messages to generate
     */
    public function generateMessages($number) {
        for( $i = 0; $i < $number; $i++) {
            $this->generateMessage();
        }
    }

    /**
     * Generates book with few tags
     */
    public function generateBook() {
        $fakeBook = new Book;

        $fakeBook->author      = $this->faker->name;
        $fakeBook->title       = $this->faker->firstName;
        $fakeBook->description = $this->faker->text;
        $fakeBook->type        = 1;
        $fakeBook->status      = 'available';
        $fakeBook->save();

        $tags_count = rand(2, 5);

        for($i = 0; $i < $tags_count; $i++) {
            $fakeTag = new Tag;
            $fakeTag->title = $this->faker->dayOfWeek;

            if (!Tag::findByTitle($fakeTag->title)) {
                $fakeTag->save();
                $fakeBook->link('tags', $fakeTag);
            } else {
                $tag = Tag::findByTitle($fakeTag->title);
                $fakeBook->link('tags', $tag);
            }
        }
    }

    /**
     * Generates books with tags
     * @param integer $number number of books to generate
     */
    public function generateBooks($number) {
        for($i = 0; $i < $number; $i++) {
            $this->generateBook();
        }
    }

    /**
     * Generates single event
     * @throws \yii\base\InvalidCallException
     */
    public function generateEvent() {
        $allUsers = User::find()->all();
        $usersCount = count($allUsers);
        if($usersCount <= 2) {
            throw new InvalidCallException('Not enough users to generate event!');
        }
        // Create event
        $event = new Event();

        // Title and description
        $event->title = $this->faker->word;
        $event->description = $this->faker->text;

        // Generate start and end date
        // Start date - in current month
        // End date - in interval between startDate and 1 week since startDate
        /** @var \DateTime $startDateTime */
        $startDateTime = $this->faker->dateTimeThisMonth;
        $toDate = clone $startDateTime;
        $toDate->add(new \DateInterval('P2D'));
        /** @var \DateTime $endDateTime */
        $endDateTime = $this->faker->dateTimeBetween($startDateTime, $toDate);
        $event->start_date = $startDateTime->format('Y-m-d');
        $event->start_time = $startDateTime->format('H:i');
        $event->end_date = $endDateTime->format('Y-m-d');
        $event->end_time = $endDateTime->format('H:i');
        // Set type
        $event->type = array_rand(Event::getTypes());
        // Set color
        $event->color = $this->faker->hexcolor;
        $event->save();

        // Random number of members
        $maxRand = min($usersCount - 1, 5);
        $eventUsersCount = rand(2, $maxRand);

        // Shuffle allUsers array to get random users
        shuffle($allUsers);
        for ($i = 0; $i < $eventUsersCount; $i++) {
            if (0 == $i) {
                $event->user_id = $allUsers[$i]->id;
                $event->save();
            }
            $event->link('users', $allUsers[$i], array('unread' => '1'));
        }
    }

    /**
     * Generate number of events
     * @param integer $number number of events to generate
     */
    public function generateEvents($number) {
        for($i = 0; $i < $number; $i++) {
            $this->generateEvent();
        }
    }
}
