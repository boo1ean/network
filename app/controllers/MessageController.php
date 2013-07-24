<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\Conversation;
use app\models\Message;

class MessageController extends Controller
{
    const EVENT_SEND_MESSAGE = "sendMessage";

    private $messageText;

    public function beforeAction($action) {

        // Check user on access
        if (Yii::$app->getUser()->getIsGuest()) {
            return Yii::$app->getResponse()->redirect('@www/');
        }

        /* Add event handler
         *
         * Use example:
         * $this->messageText = "Some message text";
         * $this->trigger(self::EVENT_SEND_MESSAGE);
         */
        $this->on(self::EVENT_SEND_MESSAGE, array($this, 'sendMessageHandler'));
        return parent::beforeAction($action);
    }

    protected function sendMessageHandler($event) {
        $email = Yii::$app->getUser()->getIdentity()->email;
        $mail = Yii::$app->getComponent('mail');
        $mail->setTo($email);
        $mail->setSubject('Private message');
        $mail->setBody($this->messageText);
        $mail->send();
    }

    public function actionIndex() {
        // Get all users conversations
        $conversations = Yii::$app->getUser()->getIdentity()->conversations;
        return $this->render('conversations', array(
            'conversations' => $conversations,
        ));

    }
    // Check user rights to access conversation
    private function checkAccess($id) {
        if(!isset($id)) {
            return true;
        }
        $conversation = Conversation::find($id);
        if(empty($conversation) || !($conversation -> isConversationMember(Yii::$app->getUser()->getIdentity()->id))) {
            return false;
        }
        return true;
    }

    public function actionConversation($id = NULL) {

        if(!isset($id) || !$this->checkAccess($id)) {
            return Yii::$app->getResponse()->redirect('message');
        }
        $conversation = Conversation::find($id);

        if (Yii::$app->getRequest()->getIsPost() && isset($_POST['body'])) {
            $message = new Message();
            $message->conversation_id = $conversation->id;
            $message->user_id = Yii::$app->getUser()->getIdentity()->id;
            $message->body = $_POST['body'];
            $message->save();
            return Yii::$app->getResponse()->redirect('message/conversation/' . $id);
        } else {
            return $this->render('messages', array(
                'conversationId'        => $conversation->id,
                'conversationTitle'     => $conversation->title,
                'conversationMembers'   => $conversation->users,
                'messages'              => $conversation->messages,
            ));
        }
    }

    public function actionMembers($id = NULL){
        if(!$this->checkAccess($id)) {
            return Yii::$app->getResponse()->redirect('message');
        }
        $conversation = Conversation::find($id);
        if(Yii::$app->getRequest()->getIsPost() && isset($_POST['members'])) {
            // if it is new conversation, create it
            if (!isset($conversation->id)) {
                $conversation = new Conversation();
                $conversation->save();
                $conversation->refresh();
                $owner =  Yii::$app->getUser()->getIdentity();
                $conversation->link('users', $owner);
            }
            $conversation = $conversation->addSubscribed($_POST['members']);
            $conversation->title = isset($_POST['title']) ? $_POST['title'] : null;
            $conversation->save();
            return Yii::$app->getResponse()->redirect('message/conversation/' . $conversation->id);
        }
        return $this->render('members', array(
            'conversationId'        => isset($conversation->id) ? $conversation->id : null,
            'conversationTitle'     => isset($conversation->title) ? $conversation->title : null,
            'conversationMembers'   => isset($conversation->users) ? $conversation->users : null,
            'unsubscribedUsers'     => isset($conversation->unsubscribedUsers) ? $conversation->unsubscribedUsers : Yii::$app->getUser()->getIdentity()->otherUsers,
        ));

    }
}