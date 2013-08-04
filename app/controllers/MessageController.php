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
            Yii::$app->getResponse()->redirect('/');
            return false;
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
        if (empty($conversation) ||
            !($conversation->isConversationMember(Yii::$app->getUser()->getIdentity()->id))) {
            return false;
        }

        return true;
    }

    public function actionConversation($id = null) {

        if (!isset($id) || !$this->checkAccess($id)) {
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

    public function actionMemberNotSubscribeList(){

        $request = Yii::$app->getComponent('request');
        if (!$request->getIsAjax() ||
            !isset($_POST['id_conversation']) ||
            !$this->checkAccess($_POST['id_conversation'])) {
            return Yii::$app->getResponse()->redirect('message');
        }

        $conversation = Conversation::find($_POST['id_conversation']);

        $users = array();
        foreach($conversation->unsubscribedUsers as $user) {
            $users[] = array(
                'id'   => $user->id,
                'name' => $user->first_name.' '.$user->last_name
            );
        }

        return json_encode($users);

    }

    public function actionMemberSave(){

        $request = Yii::$app->getComponent('request');
        if (!$request->getIsAjax() ||
            !isset($_POST['id_user']) ||
            !isset($_POST['id_conversation']) ||
            !$this->checkAccess($_POST['id_conversation'])) {
            echo 'error';
            return Yii::$app->getResponse()->redirect('message');
        }

        $conversation = Conversation::find($_POST['id_conversation']);
        $conversation = $conversation->addSubscribed(array('id' => $_POST['id_user']));
        $conversation->save();
        if ($conversation->id != $_POST['id_conversation']) {
            echo Yii::$app->getUrlManager()->createAbsoluteUrl('/message/conversation/'.$conversation->id);
        } else {
            echo 'ok';
        }
    }
}
