<?php
use Codeception\Util\Stub;
use \yii\helpers\SecurityHelper;

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
    const EMAIL         = "email@example.com";
    const PASSWORD      = "TestPassword";
    const FIRST_NAME    = "User_Firstname";
    const LAST_NAME     = "User_Lastname";

    protected function _before()
    {
        // Create user
        $this->user = new \app\models\User();
        $this->user->email = self::EMAIL;
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

    public function testSettings()
    {
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
        $this->assertTrue(SecurityHelper::validatePassword(self::PASSWORD, $this->user->password));
    }

}