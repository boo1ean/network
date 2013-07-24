<?php
namespace app\components;

use yii\base\Component;
use yii;
use app\models\User;
use \app\models\Conversation;
use \app\models\Message;

class Fixtures extends Component
{
    private $faker;
    
    function __construct() {
        $this->faker = \Faker\Factory::create();
    }
    
    /**
     * Generates single user
     */
    public function generateUser() {
        $fakeUser = new User;

        $fakeUser->email = $this->faker->email;
        $fakeUser->password = $fakeUser->hashPassword('123');
        $fakeUser->first_name = $this->faker->firstName;
        $fakeUser->last_name = $this->faker->lastName;
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
    }
        
     /**
     * Generates comversation
     */
    public function generateConversation() {  
        $fakeConversation = new Conversation;
        
        $fakeConversation->title = $this->faker->word . 'Conversation';
        $fakeConversation->save(); 
        
        /*Generate number of fake user*/
        $allUsers = User::find()
                ->all();
        $numFakeUser = rand(0, count($allUsers)-1);
        /*----------------------------*/
        
        $userToSubscribe = User::find()
                ->where(array('id'=>$allUsers[$numFakeUser]->id))
                ->one();
                
        $fakeConversation->link('users', $userToSubscribe);
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
        
        /*Generate number of fake user*/
        $allUsers = User::find()
                ->all();
        $numFakeUser = rand(0, count($allUsers)-1);
        /*----------------------------*/
        
        $fakeMessage->user_id = $allUsers[$numFakeUser]->id;
                
        /*Generate number of fake conversation*/
        $allConversations = Conversation::find()
                ->all();
        $numFakeConversation = rand(0, count($allConversations)-1);
        /*----------------------------*/
        
        $fakeMessage->conversation_id = $allConversations[$numFakeUser]->id;
             
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
}
