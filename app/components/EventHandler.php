<?php
namespace app\components;

use app\events\HandlerEvent;
use app\models\Conversation;
use app\models\User;
use app\models\Event;
use yii\base\Component;
use yii\base\InvalidParamException;
use yii\helpers\Html;

class EventHandler extends Component
{

    public function init() {
        $this->registerEventHandlers();
    }

    protected function registerEventHandlers() {
        \Yii::$app->on('CONVERSATION_MESSAGE_SENT', array($this, 'conversationMessageSentHandler'));
        \Yii::$app->on('CALENDAR_USER_INVITE',      array($this, 'calenderUserInviteHandler'));
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
        if (!isset($conversation_id, $message_body)) {
            throw new InvalidParamException('Missed required params!');
        }

        /** @var Conversation $conversation */
        $conversation = Conversation::find($conversation_id);
        $conversationUsers = $conversation->users;

        /** @var User $currentUser */
        $currentUser = \Yii::$app->getUser()->getIdentity();
        $mailTo = array();
        foreach ($conversationUsers as $user) {
            $notifications = $user->searchSetting('notifications');
            // Skip current user
            if ($user->id === $currentUser->id || !isset($notifications['messages']) || $notifications['messages'] !== true) {
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

    public function calenderUserInviteHandler($event) {
        /**
         * Prepare data
         */
        $data = $event->handlerData;
        extract($data);

        // Check data
        if (!isset($eventId, $usersId)) {
            throw new InvalidParamException('Missed required params!');
        }

        // If empty receiver list
        if (empty($usersId)) {
            return;
        }

        /** @var User $currentUser */
        $currentUser = \Yii::$app->getUser()->getIdentity();
        $mailTo = array();
        foreach ($usersId as $userId) {
            /** @var User $user */
            $user = User::find($userId);

            $notifications = $user->searchSetting('notifications');
            // Skip current user
            if ($user->id === $currentUser->id || $notifications['events'] !== true) {
                continue;
            }

            $mailTo[] = $user->email;
        }

        /** @var Event $calendarEvent */
        $calendarEvent = Event::find($eventId);

        /** @var Queue $queue */
        $queue = \Yii::$app->getComponent('queue');
        $queue->enqueue('email', array(
            'to'        =>  $mailTo,
            'subject'   =>  'New event for you: ' . $calendarEvent->title,
            'body'      =>  'You received this message, because you was invited on the event <b>' . $calendarEvent->title .
                            '</b> by user <b>' . $currentUser->first_name . ' ' . $currentUser->last_name  . '</b>.<br>' .
                            'You can see event via ' . Html::a('this link', \Yii::$app->getUrlManager()->createAbsoluteUrl('/calendar/eventpage/'.$eventId)),
        ));
    }

}