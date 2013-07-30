<?php
namespace app\components;

use yii\base\Component;
use yii;
use app\models\User;
use \app\models\Conversation;
use \app\models\Message;
use \app\models\Book;
use \app\models\Tag;

class Fixtures extends Component
{
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
     * Generates comversation
     */
    public function generateConversation() {  
        $fakeConversation = new Conversation;
        
        $fakeConversation->title = $this->faker->word . 'Conversation';
        $fakeConversation->save(); 
        
        $idArr = array();
        for($i = 0; $i < 2; $i++) {
            /*Generate number of fake user*/
            $allUsers = User::find()
                    ->all();
            $numFakeUser = rand(0, count($allUsers)-1);
            /*----------------------------*/
        
            $userToSubscribe = User::find()
                    ->where(array('id' => $allUsers[$numFakeUser]->id))
                    ->one();
            $idArr[$i] = $userToSubscribe->id;
        }
        
        $fakeConversation->addSubscribed($idArr);
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
        
        /*Generate number of fake conversation*/
        $allConversations = Conversation::find()->all();
        $numFakeConversation = rand(0, count($allConversations)-1);
        /*----------------------------*/
        
        $fakeMessage->conversation_id = $allConversations[$numFakeConversation]->id;
        $fakeConversation->id = $fakeMessage->conversation_id;
             
        /*Generate number of fake user, whos is participant in conversation.*/
        $allUsers = User::find()
                    ->all();
        $numFakeUser = 0;
        $idFakeUser = 0;
        for(;;) {
            $numFakeUser = rand(0, count($allUsers)-1);
            
            $idFakeUser = $allUsers[$numFakeUser]->id;
            $boo = $fakeConversation->isConversationMember($idFakeUser);
            if($boo) {
                break;
            }
        }
        /*----------------------------*/
        
        $fakeMessage->user_id = $idFakeUser;
        
        $fakeMessage->body = $this->faker->text;
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
            $fakeTag->title = $this->faker->company;
            $fakeTag->save();
            $fakeBook->link('tags', $fakeTag);
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
}
