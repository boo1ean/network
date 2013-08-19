<?php
namespace app\components;

use app\events\HandlerEvent;
use app\models\Conversation;
use app\models\User;
use yii\base\Component;
use yii\base\InvalidParamException;

class EventHandler extends Component
{

    public function init() {
        $this->registerEventHandlers();
    }

    protected function registerEventHandlers() {
        \Yii::$app->on('CONVERSATION_MESSAGE_SENT', array($this, 'conversationMessageSentHandler'));
    }

    /**
     * Method for handle CONVERSATION_MESSAGE_SENT event
     * @param $event HandlerEvent
     * @throws InvalidParamException
     */
    public function conversationMessageSentHandler($event) {
        /**
         * Prepare data
         */
        $data = $event->handlerData;
        extract($data);

        // Check data
        if (!isset($conversation_id) || !isset($message_body)) {
            throw new InvalidParamException('Missed required params!');
        }

        /** @var Conversation $conversation */
        $conversation = Conversation::find($conversation_id);
        $conversationUsers = $conversation->users;

        /** @var User $currentUser */
        $currentUser = \Yii::$app->getUser()->getIdentity();
        $mailTo = array();
        foreach ($conversationUsers as $user) {
            // Skip current user
            if ($user->id === $currentUser->id || $user->searchSetting('sendNotifications') !== 'yes') {
                continue;
            }

            $mailTo[] = $user->email;
        }

        // Not neeed send messages
        if (empty($mailTo)) {
            return;
        }

        /** @var Queue $queue */
        $queue = \Yii::$app->getComponent('queue');
        $queue->enqueue('email', array(
            'to'        =>  $mailTo,
            'subject'   =>  'New message in conversation: ' . $conversation->title,
            'body'      =>  'You received this message, because you are a member of ' . $conversation->title . ' conversation. ' .
                            'New message: ' . $message_body,
        ));
    }

}