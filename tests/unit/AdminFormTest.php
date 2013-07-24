<?php
use Codeception\Util\Stub;
use \app\models\AdminForm;
use \app\models\User;
use Faker\autoload;

class AdminFormTest extends \Codeception\TestCase\Test
{
    /**
     * @var \app\models\AdminForm
     */
    private $adminForm;

    /**
     * @var \app\models\User
     */
    private $user;

    protected function _after()
    {
        // Remove user
        $this->user->delete();
    }

    protected function _before()
    {
        $faker = Faker\Factory::create();

        // Create adminForm instance
        $this->adminForm = new AdminForm();
        $this->adminForm->email = $faker->email;

        // Create user instance
        $this->user = new User();
        $this->user->email = $this->adminForm->email;
        $this->user->password = User::hashPassword($faker->word);
        $this->user->first_name = $faker->firstname;
        $this->user->last_name = $faker->lastname;

        // for urlManager component
        $_SERVER['HTTP_HOST'] = $faker->domainName;
    }

    public function testMatchEmail()
    {
        $this->assertTrue($this->adminForm->validate());

        $this->user->save();
        $this->assertFalse($this->adminForm->validate());

        $this->adminForm->email .= 'some';
        $this->assertTrue($this->adminForm->validate());
    }

    public function testSendInvite()
    {
        // existing email
        $this->user->save();
        $this->assertFalse($this->adminForm->sendInvite());

        // invalid email
        $this->adminForm->email = $this->user->first_name;
        $this->assertFalse($this->adminForm->sendInvite());

        // empty email
        $this->adminForm->email = '';
        $this->assertFalse($this->adminForm->sendInvite());
    }

    public function testSendInviteTest()
    {
        // valid email
        $this->assertStringStartsWith('For easy', $this->adminForm->sendInviteTest());

        // existing email
        $this->assertFalse($this->adminForm->sendInviteTest());

        // invalid email
        $this->adminForm->email = $this->user->first_name;
        $this->assertFalse($this->adminForm->sendInviteTest());

        // empty email
        $this->adminForm->email = '';
        $this->assertFalse($this->adminForm->sendInviteTest());
    }
}