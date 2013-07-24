<?php
namespace app\components;

use yii\base\Component;
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
        
       // $fakeConversation
    }     
    
    /**
     * Generates message
     */
    public function generateMessage() {  
        $fakeMessage = new Message;
        
        $fakeMessage->user_id = 1;
        $fakeMessage->conversation_id = 1;
        $fakeMessage->body = $this->faker->text;
        $fakeMessage->save();
    }
}
