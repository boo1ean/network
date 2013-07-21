<?php
use Codeception\Util\Stub;
use \yii\helpers\Security;
use \app\models\User;
use Faker\autoload;

class UserTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    /**
     * @var \app\models\User
     */
    private $user;

    // Default const
    const PASSWORD      = "TestPassword";
    const FIRST_NAME    = "User_Firstname";
    const LAST_NAME     = "User_Lastname";

    protected function _before()
    {
        $faker = Faker\Factory::create();
        // Create user
        $this->user = new User();
        $this->user->email = $faker->email;
        $this->user->password = self::PASSWORD;
        $this->user->first_name = self::FIRST_NAME;
        $this->user->last_name = self::LAST_NAME;
        $this->user->save();
    }

    protected function _after()
    {
        // Remove user
        $this->user->delete();
    }

    public function testGetId() {

        // Get user by email
        $user = User::findByEmail($this->user->email);
        $this->assertEquals($user->id, $this->user->getId());
    }

    public function testFind() {

        // Test findByEmail
        $user = User::findByEmail($this->user->email);
        $this->assertEquals($this->user->id, $user->id);
        $this->assertEquals($this->user->email, $user->email);

        // Test findIdentity
        $user = User::findIdentity($this->user->id);
        $this->assertEquals($this->user->id, $user->id);
        $this->assertEquals($this->user->email, $user->email);
    }

    public function testSettings() {
        // Get empty array by default (beforeSave)
        $setting = $this->user->setting;
        $this->assertEquals(array(), $setting);

        // Check setter and getter
        $expected = array("key" => "value", array(array("key" => "value")));
        $this->user->setting = $expected;
        $actual = $this->user->setting;
        $this->assertEquals($expected, $actual);

        // Check User::addSetting method
        $addArray = array("omg" => "omg value");
        $this->user->addSetting("omg", "omg value");
        $this->assertEquals($this->user->setting, array_merge($expected, $addArray));

        // Check User::searchSetting
        $actual = $this->user->searchSetting("omg");
        $this->assertEquals("omg value", $actual);

    }

    public function testValidatePassword() {
        // Check User::validatePassword
        $this->assertTrue($this->user->validatePassword(self::PASSWORD));
        $this->assertTrue(Security::validatePassword(self::PASSWORD, $this->user->password));
    }

}