<?php

namespace app\controllers;

use app\models\AddMessageForm;
use yii;
use yii\web\Controller;
use app\models\Conversation;

class MessageController extends Controller
{
    const EVENT_SEND_MESSAGE = "sendMessage";

    private $messageText;

    public function beforeAction($action) {

        // Check user on access
        if (Yii::$app->getUser()->getIsGuest()) {
            return Yii::$app->getResponse()->redirect('@www/');
        }

        // Add event handler and
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
        // Send message
        //$this->messageText = "Some message text";
        //$this->trigger(self::EVENT_SEND_MESSAGE);

        // TODO: replace find()->all() to findConversationsByUser() when it will be implemented
        $conversations = Conversation::find()->all();
        return $this->render('conversations', array('conversations' => $conversations));

    }

    public function actionConversation($id = NULL) {

        // If function called without parameters
        if(!isset($id) || Conversation::find($id) == NULL) {
            return Yii::$app->getResponse()->redirect('message');
        }

        // Create new form object and set conversation id
        $addMessageForm = new AddMessageForm();
        $addMessageForm->conversation_id = $id;

         // If data was successfully loaded
        if ($addMessageForm->load($_POST) && $addMessageForm->addMessage()) {
            return Yii::$app->getResponse()->redirect('message/conversation/' . $id);
        } else {
            $conversation = Conversation::find($id);
            $messages = $conversation->messages;
            return $this->render('messages', array(
                'conversationId'    => $id,
                'conversationTitle' => $conversation->title,
                'messages'          => $messages,
                'model'             => $addMessageForm,
            ));
        }
    }
}