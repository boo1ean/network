<?php
namespace app\components;

use yii\base\Component;
use \Faker\Factory;

class Fixtures extends Component
{
    private $faker;
    
    /**
     * Generates single user
     * @param array $data custom user data (optional)
     */
    public function generateUser() {
        // Generate fake data, populate model and store to db
        $faker = Faker\Factory::create();
        $data['email'] = $faker->email;
        $data['password'] = $faker->word;
        $data['first_name'] = $faker->firstName;
        $data['last_name'] = $faker->lastName;
        
        return $data;
    }


    /**
     * Generates users
     * @param integer $number number of users to generate
     */
    public function generateUsers($number) {
        // call user method $number times
        for( $i = 0; $i < $number; $i++) {
            $data[$i] = generateUser();
        }
        
        return $data;
    }
    
     /**
     * Generates comversation
     */
    public function generateConversation() {  
        $faker = Faker\Factory::create();
        $data['title'] = $faker->title;
        
        return $data;
    }     
    
    /**
     * Generates message
     */
    public function generateMessage() {
        $faker = Faker\Factory::create();
        $data['body'] =  $faker->text;
        
        return $data;
    }
}
