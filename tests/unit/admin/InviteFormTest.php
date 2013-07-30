<?php
use Codeception\Util\Stub;
use \app\models\admin\InviteForm;
use \app\models\User;

class InviteFormTest extends \Codeception\TestCase\Test
{
    /**
     * @var \app\models\InviteForm
     */
    private $inviteForm;

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
        $faker = \Faker\Factory::create();

        // Create adminForm instance
        $this->inviteForm           = new InviteForm();
        $this->inviteForm->email    = $faker->email;
        $this->inviteForm->password = User::hashPassword($faker->word);

        // Create user instance
        $this->user = new User();
        $this->user->email      = $this->inviteForm->email;
        $this->user->password   = $this->inviteForm->password;
        $this->user->first_name = $faker->firstname;
        $this->user->last_name  = $faker->lastname;

        // for urlManager component
        $_SERVER['HTTP_HOST'] = $faker->domainName;
    }

    public function testMatchEmail()
    {
        $this->assertTrue($this->inviteForm->validate());

        $this->user->save();
        $this->assertFalse($this->inviteForm->validate());

        $this->inviteForm->email .= 'some';
        $this->assertTrue($this->inviteForm->validate());
    }

    public function testSendInvite()
    {
        // existing email
        $this->user->save();
        $this->assertFalse($this->inviteForm->sendInvite());

        // invalid email
        $this->inviteForm->email = $this->user->first_name;
        $this->assertFalse($this->inviteForm->sendInvite());

        // empty email
        $this->inviteForm->email = '';
        $this->assertFalse($this->inviteForm->sendInvite());
    }

    public function testSendInviteTest()
    {
        // valid email
        $this->assertStringStartsWith('For easy', $this->inviteForm->sendInviteTest());

        // existing email
        $this->assertFalse($this->inviteForm->sendInviteTest());

        // invalid email
        $this->inviteForm->email = $this->user->first_name;
        $this->assertFalse($this->inviteForm->sendInviteTest());

        // empty email
        $this->inviteForm->email = '';
        $this->assertFalse($this->inviteForm->sendInviteTest());
    }
}