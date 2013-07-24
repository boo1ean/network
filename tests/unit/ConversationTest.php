<?php
use Codeception\Util\Stub;
use \app\models\Conversation;
use \app\models\User;
use \app\models\Message;

class ConversationTest extends \Codeception\TestCase\Test
{
    const USER_COUNT = 3;
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    /**
     * @var Conversation
     */
    private $conversation;

    /**
     * @var
     */
    private $users;

    protected function _before() {
        $this->conversation = new Conversation();
        $this->conversation->save();
        $this->users = array();
        for ($i = 0; $i < self::USER_COUNT; $i++) {
            $user = new User();
            $user->save();
            $this->users[$i] = $user;
        }
    }

    protected function _after() {
        for ($i = 0; $i < self::USER_COUNT; $i++) {
            $this->users[$i]->delete();
        }
        $this->conversation->delete();
    }

    // tests
    public function testIsPrivate() {

        $this->conversation->private = 1;
        $this->assertTrue($this->conversation->isPrivate());

        $this->conversation->private = 0;
        $this->assertFalse($this->conversation->isPrivate());
    }

    public function testCopyToMultiChat() {

        $this->conversation->private = 1;
        $this->conversation->title = "testTitle";
        $this->conversation->addSubscribed(array(0, 1));

        $newConversation = $this->conversation->copyToMultiChat();

        // Copy to multichat must set private to 0
        $expectedPrivate = 0;
        $this->assertEquals($expectedPrivate, $newConversation->private);

        // Titles of conversations must be the same
        $this->assertEquals($this->conversation->title, $newConversation->title);

        // Subscribed users must to be saved
        $this->assertEquals($this->conversation->users, $newConversation->users);

        // Change users array to be not the same as in original conversation
        $users = $newConversation->users;
        $users[] = new User();
        $this->assertNotEquals($this->conversation->users, $users);
    }

    public function testIsConversationMember() {

        $this->conversation->addSubscribed(array($this->users[0]->id));
        // Test subscribed
        $this->assertTrue($this->conversation->IsConversationMember($this->users[0]->id));
        // Test unsubscribed
        $this->assertFalse($this->conversation->isConversationMember($this->users[1]->id));
    }

    public function testGetUsers() {

        $this->assertEmpty($this->conversation->users);
        $idArray = array();     // Users id array for subscribing
        $users = array();       // Array of expected users
        for ($i = 0; $i < self::USER_COUNT; $i++) {
            $idArray[] = $this->users[$i]->id;
            $users[] = User::find($this->users[$i]->id);
        }
        $this->conversation->addSubscribed($idArray);

        $this->assertNotEmpty($this->conversation->users);
        $this->assertEquals($users, $this->conversation->users);
    }

    public function testGetUnsubscribedUsers() {

        $idToSubscribe = $this->users[0]->id;
        $subscribedUser = User::find($idToSubscribe);

        // Unsubscribed users exist
        $this->assertNotEmpty($this->conversation->unsubscribedUsers);
        $unsubscribedCount = count($this->conversation->unsubscribedUsers);
        $this->assertContains($subscribedUser, $this->conversation->unsubscribedUsers);

        $this->conversation->addSubscribed(array($idToSubscribe));
        // Now unsubscribed users don't contain subscribed user
        $this->assertNotContains($subscribedUser, $this->conversation->unsubscribedUsers);
        $this->assertLessThan($unsubscribedCount, count($this->conversation->unsubscribedUsers));
    }

}