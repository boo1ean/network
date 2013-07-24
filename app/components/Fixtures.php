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
        $allConversation = Conversation::find()
                ->all();
        $numFakeConversation = rand(0, count($allConversation)-1);
        /*----------------------------*/
        
        $fakeMessage->conversation_id = $allConversation[$numFakeUser]->id;
             
        $fakeMessage->body = $this->faker->text;
        $fakeMessage->save();
    }
}
