<?php

namespace app\controllers;

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
            return Yii::$app->getResponse()->redirect('@www');
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
        $this->messageText = "Some message text";
        $this->trigger(self::EVENT_SEND_MESSAGE);

    }

    public function actionConversation($id = NULL) {
        if(!isset($id))
        {
            // TODO: replace find()->all() to findConversationsByUser() when it will be implemented
            $conversations = Conversation::find()->all();
            return $this->render('conversations', array('conversations' => $conversations));
        }
    }
}