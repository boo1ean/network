<?php

namespace app\controllers;

use ___PHPSTORM_HELPERS\object;
use yii;
use yii\web\Controller;
use app\models\Conversation;
use app\models\Message;
use app\models\User;

class MessageController extends PjaxController
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
        $mail  = Yii::$app->getComponent('mail');

        $mail->setTo($email);
        $mail->setSubject('Private message');
        $mail->setBody($this->messageText);
        $mail->send();
    }

    public function actionIndex() {
        // Get all users conversations
        $conversations = Yii::$app->getUser()->getIdentity()->conversations;
        $viewParams = array();

        foreach($conversations as $conversation) {
            $row = array();

            $row['id']      = $conversation->id;
            $row['title']   = $conversation->title;
            $row['private'] = $conversation->isPrivate();
            $row['users']   = array();

            foreach ($conversation->users as $user) {
                if (Yii::$app->getUser()->getIdentity()->id != $user->id) {
                    $row['users'][] = $user;
                }
            }

            $row['unread'] = $conversation->isUnread(Yii::$app->getUser()->getIdentity()->id);
            $message = Message::getLastInConversation($conversation->id);

            if ($message != null) {
                $row['lastMessage']       = $message;
                $lastMessageUser          = $message->user;
                $row['lastMessageUser']   = $lastMessageUser->userName;
                $row['lastMessageAvatar'] = $lastMessageUser->avatar;
            }

            $viewParams[] = $row;
        }
        return $this->render('conversations', array(
            'conversations' => $viewParams,
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
            $message->user_id         = Yii::$app->getUser()->getIdentity()->id;
            $message->body            = $_POST['body'];

            $message->save();
            return Yii::$app->getResponse()->redirect('message/conversation/' . $id);
        } else {
            // Mark conversation as read
            $conversation->markAsRead(Yii::$app->getUser()->getIdentity()->id);
            return $this->render('messages', array(
                'conversationCreator' => $conversation->getCreator(),
                'conversationId'      => $conversation->id,
                'conversationMembers' => $conversation->users,
                'conversationTitle'   => $conversation->title,
                'messages'            => $conversation->messages,
            ));
        }
    }

    public function actionConversationCreate() {
        if (!Yii::$app->getRequest()->getIsAjax()) {
            return Yii::$app->getResponse()->redirect('message');
        }

        $this->layout = 'block';
        $conversation = new Conversation();

        if(Yii::$app->getRequest()->getIsPost()) {
            if(isset($_POST['members']) && count($_POST['members'] > 0)) {

                if(isset($_POST['message']) && $_POST['message'] != null) {

                    $owner =  Yii::$app->getUser()->getIdentity();

                    $conversation->creator = $owner->id;
                    $conversation->save();
                    $conversation->refresh();
                    $conversation->link('users', $owner);

                    // Add message to conversation
                    $message = new Message();
                    $message->user_id = $owner->id;
                    $message->conversation_id = $conversation->id;
                    $message->body = $_POST['message'];
                    $message->save();

                    foreach($_POST['members'] as $key => $value) {
                    $user         = User::find($key);
                    $conversation = $conversation->addSubscribed($user);
                    }

                    $conversation->title = isset($_POST['Conversation']['title']) ? $_POST['Conversation']['title'] : null;
                    $conversation->save();

                    return json_encode(array('redirect' => 'message/conversation/' . $conversation->id));
                } else {
                    $result = array(
                        'status' => 'error',
                        'errors' => array('message' => 'Conversation must have first message')
                    );
                    return json_encode($result);
                }
            } else {
                $result = array(
                    'status' => 'error',
                    'errors' => array('new-member-list' => 'Conversation must have 1 or more members')
                );
                return json_encode($result);
            }
        } else {
            $param = array(
                'model' => $conversation
            );

            return $this->render('conversationCreate', $param);
        }
    }

    public function actionMemberNotSubscribeList() {

        if (!Yii::$app->getRequest()->getIsAjax()) {
            return Yii::$app->getResponse()->redirect('message');
        }

        if (isset($_POST['id_conversation']) && !$this->checkAccess($_POST['id_conversation'])) {
            return Yii::$app->getResponse()->redirect('message');
        }

        $conversation = isset($_POST['id_conversation']) ? Conversation::find($_POST['id_conversation']) : new Conversation();
        $users        = array();

        foreach ($conversation->unsubscribedUsers as $user) {

            if($user->id == Yii::$app->getUser()->getIdentity()->id) {
                continue;
            }

            $users[] = array(
                'id'   => $user->id,
                'name' => $user->first_name.' '.$user->last_name
            );
        }

        return json_encode($users);
    }

    public function actionMemberSave() {

        if (!Yii::$app->getRequest()->getIsAjax() ||
            !isset($_POST['id_user']) ||
            !isset($_POST['id_conversation']) ||
            !$this->checkAccess($_POST['id_conversation'])) {
            echo 'error';
            return Yii::$app->getResponse()->redirect('message');
        }

        $conversation = Conversation::find($_POST['id_conversation']);
        $conversation = $conversation->addSubscribed(User::find($_POST['id_user']));
        $conversation->save();

        if ($conversation->id != $_POST['id_conversation']) {
            echo Yii::$app->getUrlManager()->createAbsoluteUrl('/message/conversation/'.$conversation->id);
        } else {
            echo 'ok';
        }
    }
}
