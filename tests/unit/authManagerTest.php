<?php
use Codeception\Util\Stub;
use \app\models\User;

class authManagerTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    /**
     * @var User
     */
    private $userAdmin;
    private $userRegular;

    protected function _before()
    {
        // Create regular user
        $this->userRegular = new User();
        $this->userRegular->save();

        $this->userAdmin = new User();
        $this->userAdmin->type = USER::TYPE_ADMIN;
        $this->userAdmin->save();
    }

    protected function _after()
    {
        $this->userAdmin->delete();
        $this->userRegular->delete();
    }

    // tests
    public function testCheckAccess()
    {
        Yii::$app->getUser()->login($this->userAdmin);
        $this->assertTrue(Yii::$app->getUser()->checkAccess('admin', array(), false));

        Yii::$app->getUser()->login($this->userRegular);
        $this->assertFalse(Yii::$app->getUser()->checkAccess('admin', array(), false));
    }

}