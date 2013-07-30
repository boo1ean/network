<?php
use Codeception\Util\Stub;
use \app\models\admin\UserForm;
use \app\models\User;

class UserFormTest extends \Codeception\TestCase\Test
{
    /**
     * @const integer count of test users in the database
     */
    const USER_TEST_COUNT = 10;

    /**
     * @const integer ID of test user
     */
    const USER_ID = 5;

    /**
     * @var array post imitation
     */
    private $post;

    /**
     * @var integer current count of users in the database
     */
    private $user_current_count;

    /**
     * @var \app\models\UserForm
     */
    private $userForm;

    /**
     * @var array of \app\models\user
     */
    private $users;

    protected function _after()
    {
        // Remove users
        foreach($this->users as $user) {
            $user->delete();
        }
    }

    protected function _before()
    {
        $faker = \Faker\Factory::create();

        // current count of users
        $user = new User();
        $this->user_current_count = $user->find()->count();

        // Create users instance
        for ($i = 0; $i < self::USER_TEST_COUNT; $i++) {
            $user = new User();
            $user->email      = $faker->email;
            $user->first_name = $faker->firstname;
            $user->is_active  = 1;
            $user->last_name  = $faker->lastname;
            $user->password   = $faker->word;
            $user->save();

            $this->users[] = $user;
        }

        // generate userForm settings
        $this->userForm         = new UserForm();
        $this->userForm->limit  = self::USER_TEST_COUNT + $this->user_current_count;
        $this->userForm->offset = 0;

        // generate valid post data
        $this->post['email']           = $faker->email;
        $this->post['first_name']      = $faker->firstname;
        $this->post['last_name']       = $faker->lastname;
        $this->post['password']        = $faker->word;
        $this->post['repeat_password'] = $this->post['password'];
    }

    public function testUserBlock() {
        $this->userForm->scenario = 'block';
        $user_edit = $this->users[self::USER_ID];

        // when calling without post data
        $this->assertFalse($this->userForm->userBlock());

        $this->userForm->is_block = 1;

        // when calling without id user for edit
        $this->assertFalse($this->userForm->userBlock());

        $this->userForm->id_edit = $user_edit->id;

        // block user
        $this->assertTrue($this->userForm->userBlock());
        $user_edit = User::find($user_edit->id);
        $this->assertEquals($user_edit->is_active, 0);

        // unblock user
        $this->userForm->is_block = 0;
        $this->assertTrue($this->userForm->userBlock());
        $user_edit = User::findIdentity($user_edit->id);
        $this->assertEquals($user_edit->is_active, 1);
    }

    public function testUserEdit() {
        $user_edit = $this->users[self::USER_ID];

        // when calling without post data
        $this->assertFalse($this->userForm->userEdit());

        // when calling without id user for edit
        $this->userForm->is_first = 1;
        $this->assertFalse($this->userForm->userEdit());

        // when user edit form was opening
        $this->userForm->scenario = 'isFirst';
        $this->userForm->id_edit  = $user_edit->id;
        $this->assertTrue($this->userForm->userEdit());

        $this->assertEquals($this->userForm->email,      $user_edit->email);
        $this->assertEquals($this->userForm->first_name, $user_edit->first_name);
        $this->assertEquals($this->userForm->last_name,  $user_edit->last_name);

        // prepare for submit dat
        $this->userForm->scenario        = 'default';
        $this->userForm->is_first        = 0;
        $this->userForm->email           = $this->post['email'];
        $this->userForm->first_name      = $this->post['first_name'];
        $this->userForm->last_name       = $this->post['last_name'];
        $this->userForm->password        = $this->post['password'];
        $this->userForm->repeat_password = $this->post['repeat_password'];

        // invalid email
        $this->userForm->email = 'invalid email';
        $this->assertFalse($this->userForm->userEdit());
        $this->userForm->email = $this->post['email'];

        // invalid repeat password
        $this->userForm->repeat_password = 'invalid repeat password';
        $this->assertFalse($this->userForm->userEdit());
        $this->userForm->repeat_password = $this->post['repeat_password'];

        // valid data
        $this->assertTrue($this->userForm->userEdit());

        $this->assertEquals($this->post['email'],      $this->userForm->email);
        $this->assertEquals($this->post['first_name'], $this->userForm->first_name);
        $this->assertEquals($this->post['last_name'],  $this->userForm->last_name);
        $this->assertEquals($this->post['password'],   $this->userForm->password);
    }

    public function testUserList() {
        // login in some user
        Yii::$app->getUser()->login($this->users[self::USER_ID], 0);
        $result = $this->userForm->userList();

        // when pagination don't needed
        $this->assertArrayHasKey('pagination', $result);
        $this->assertArrayHasKey('users', $result);
        $this->assertNull($result['pagination']);
        $this->assertEquals(count($result['users']), $this->userForm->limit - 1);

        // pagination working good
        // (deduct 2 cause of 1 user was logged in and 1 to be sure about pagination realy needed)
        $this->userForm->limit  -= 2;
        $this->userForm->offset  = $this->userForm->limit;

        $result = $this->userForm->userList();
        $this->assertNotNull($result['pagination']);
        $this->assertEquals(count($result['users']), 1);
    }
}