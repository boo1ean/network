<?php

namespace app\controllers;

use app\components\Storage;
use app\events\HandlerEvent;
use yii;
use app\models\Conversation;
use app\models\Message;
use app\models\User;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;



class ConversationController extends PjaxController
{

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

    public function actionConversationCreate() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->createAbsoluteUrl('/conversation/conversation-list'),
            'status'   => 'ok'
        );

        if (!Yii::$app->getRequest()->getIsAjax()) {
            $result['status'] = 'redirect';
        }

        if('ok' == $result['status']) {

            $this->layout = 'block';
            $conversation = new Conversation();

            if(Yii::$app->getRequest()->getIsPost()) {

                if(!isset($_POST['members']) || count($_POST['members']) == 0) {
                    $result['status'] = 'error';
                    $result['errors']['new-member-list'] = 'Conversation must have 1 or more members';
                }

                if(!isset($_POST['message']) || $_POST['message'] == null) {
                    $result['status'] = 'error';
                    $result['errors']['message'] = 'Conversation must have first message';
                }

                if('ok' == $result['status']) {
                    $owner = Yii::$app->getUser()->getIdentity();

                    $conversation->creator = $owner->id;
                    $conversation->save();
                    $conversation->refresh();
                    $conversation->link('users', $owner);

                    foreach($_POST['members'] as $key => $value) {
                        $user         = User::find($key);
                        $conversation = $conversation->addSubscribed($user);
                    }

                    // Add message to conversation
                    $message = new Message();
                    $message->user_id         = $owner->id;
                    $message->conversation_id = $conversation->id;
                    $message->body            = $_POST['message'];
                    $message->save();

                    $conversation->title = isset($_POST['Conversation']['title']) ? $_POST['Conversation']['title'] : null;
                    $conversation->save();

                    $result['redirect'] = Yii::$app->getUrlManager()->createAbsoluteUrl('/conversation/' . $conversation->id);
                }
            } else {
                $param = array(
                    'model' => $conversation
                );
                $result['html'] = $this->render('conversationCreate', $param);
            }
        }

        return json_encode($result);
    }

    public function actionConversationList() {
        // Get all users conversations
        $conversations = Yii::$app->getUser()->getIdentity()->conversations;
        $viewParams = array();

        foreach($conversations as $conversation) {
            $row = array();

            $row['id']      = $conversation->id;
            $row['title']   = $conversation->conversationTitle;
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
                $message->body = preg_replace('/\[\[attachment(\d+)_([a-z]+)_([a-z0-9]+)\]\]/', '[Attachment]', $message->body);
                $row['lastMessage']       = $message;
                $lastMessageUser          = $message->user;
                $row['lastMessageUser']   = $lastMessageUser->userName;
                $row['lastMessageAvatar'] = $lastMessageUser->avatar;
            }

            $viewParams[] = $row;
        }
        return $this->render('conversationList', array(
            'conversations' => $viewParams,
        ));
    }

    /*
     * get conversation
     */
    public function actionIndex($id = null) {

        if (!is_numeric($id) || !$this->checkAccess($id)) {
            return Yii::$app->getResponse()->redirect('conversation/conversation-list');
        }

        $conversation = Conversation::find($id);
        $creator      = $conversation->getCreator();
        $user         = Yii::$app->getUser()->getIdentity();

        // Parse messages on attachments
        foreach ($conversation->messages as $message) {
            $message->body = $this->handleAttachment($message->body);
        }

        // Mark conversation as read
        $conversation->markAsRead($user->id);
        return $this->render('conversation', array(
            'conversationCreator' => $creator,
            'conversationId'      => $conversation->id,
            'conversationMembers' => $conversation->users,
            'conversationTitle'   => $conversation->conversationTitle,
            'is_creator'          => $user->id == $creator->id,
            'messages'            => $conversation->messages,
            'user'                => $user
        ));
    }

    public function actionMemberNotSubscribeList() {

        if (!Yii::$app->getRequest()->getIsAjax()) {
            return Yii::$app->getResponse()->redirect('conversation-list');
        }

        if (isset($_POST['id_conversation']) && !$this->checkAccess($_POST['id_conversation'])) {
            return json_encode(array());
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

    public function actionMemberRemove() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->createAbsoluteUrl('/conversation/conversation-list'),
            'status'   => 'ok'
        );
        $user = Yii::$app->getUser()->getIdentity();

        if (!Yii::$app->getRequest()->getIsAjax() ||
            !isset($_POST['id_user']) ||
            !isset($_POST['id_conversation']) ||
            !$this->checkAccess($_POST['id_conversation']) && $_POST['id_user'] != $user->id) {
            $result['status'] = 'redirect';
        }

        $conversation = Conversation::find($_POST['id_conversation']);

        if($_POST['id_user'] != $user->id && $conversation->getCreator()->id != $user->id) {
            $result['status'] = 'redirect';
        }

        if('ok' == $result['status']) {
            $conversation = Conversation::find($_POST['id_conversation']);
            $conversation->deleteMember($_POST['id_user']);
        }

        if ($_POST['id_user'] == $user->id) {
            $result['status'] = 'redirect';
        }

        return json_encode($result);
    }

    public function actionMemberSave() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->createAbsoluteUrl('/conversation/conversation-list'),
            'status'   => 'ok'
        );

        if (!Yii::$app->getRequest()->getIsAjax() ||
            !isset($_POST['id_user']) ||
            !isset($_POST['id_conversation']) ||
            !$this->checkAccess($_POST['id_conversation'])) {
            $result['status'] = 'redirect';
        }

        if('ok' == $result['status']) {
            $conversation = Conversation::find($_POST['id_conversation']);
            $conversation = $conversation->addSubscribed(User::find($_POST['id_user']));
            $conversation->save();
        }

        if ($conversation->id != $_POST['id_conversation']) {
           $result['status']   = 'redirect';
           $result['redirect'] = Yii::$app->getUrlManager()->createAbsoluteUrl('/conversation/'.$conversation->id);
        } else {
            $result['status'] = 'ok';
        }

        return json_encode($result);
    }

    public function actionMessageSend() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->createAbsoluteUrl('/conversation/conversation-list'),
            'status'   => 'ok'
        );

        if (!Yii::$app->getRequest()->getIsAjax() || !isset($_POST['id']) || !$this->checkAccess($_POST['id'])) {
            $result['status'] = 'redirect';
        }

        if('ok' == $result['status']) {
            $conversation = Conversation::find($_POST['id']);
            $message      = new Message();

            $message->conversation_id = $conversation->id;
            $message->user_id         = Yii::$app->getUser()->getIdentity()->id;
            $message->body            = $_POST['body'];

            // Handle attachments
            $attachments = json_decode($_POST['attachments']);

            // New line for attachments
            if (count($attachments) > 0) {
                $message->body .= '<br>';
            }

            foreach ($attachments as $attachment) {
                // Get attachment parts
                list($res_id, $res_type, $sign) = explode('_', $attachment);

                // Invalid signature - skip attachment
                if ($sign !== $sign = md5(Yii::$app->params['secretString'] . '_' . $res_id . '_' . $res_type . '_' . Yii::$app->params['secretString'])) {
                    continue;
                }

                // Add attachment to body message
                $message->body .= ' [[attachment' . $res_id . '_' . $res_type . '_' . $sign . ']]';
            }

            // Send event for notification
            $event = new HandlerEvent(array(
                'conversation_id'           => $conversation->id,
                'message_body'              => $message->body,
            ));
            Yii::$app->trigger('CONVERSATION_MESSAGE_SENT', $event);

            $message->save();

            // Handle attachments
            $message->body = $this->handleAttachment($message->body);

            $result['status']  = count($message->errors) > 0 ? 'error' : $result['status'];
            $result['errors']  = $message->errors;
            $result['message'] = $message->toArray();
        }

        return json_encode($result);
    }

    public function actionPrivate($id = null) {
        /**
         * @var $currentUser User
         */
        $currentUser = Yii::$app->getUser()->getIdentity();
        $currentId = $currentUser->id;
        if ($id == null || $id == $currentId) {
            return Yii::$app->getResponse()->redirect('/');
        }
        /**
         * @var $recipient User
         */
        $recipient = User::find($id);
        if ($recipient == null) {
            return Yii::$app->getResponse()->redirect('/');
        }
        // Search private conversation with specified user
        /**
         * @var $conversation Conversation
         */
        $conversation = Conversation::getPrivateConversation($currentId, $id);
        // If conversation was found - open it, if was not found - create and open
        if($conversation == null) {
            $conversation = new Conversation();
            $conversation->creator = $currentUser->id;
            $conversation->save();
            $conversation->addSubscribed($currentUser);
            $conversation->addSubscribed($recipient);
        }
        return Yii::$app->getResponse()->redirect('/conversation/' . $conversation->id);

    }

    public function actionUpdateTitle() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->createAbsoluteUrl('/conversation/conversation-list'),
            'status'   => 'ok'
        );
        if (!Yii::$app->getRequest()->getIsAjax() || !isset($_POST['id']) || !isset($_POST['title'])) {
            $result['status'] = 'redirect';
        }

        $conversation = Conversation::find($_POST['id']);
        if ($conversation == null) {
            $result['status'] = 'redirect';
        } else {
            $conversation->title = $_POST['title'];
            $conversation->save();
        }
        return json_encode($result);
    }

    public function actionFilesUpload() {
        $file = UploadedFile::getInstanceByName('qqfile');
        $mime = FileHelper::getMimeType($file->getTempName());

        /** @var Storage $storage */
        $storage = Yii::$app->getComponent('storage');
        $resource_id = $storage->save($file);

        if ($resource_id === false)
        {
            return json_encode(array('success' => false));
        }

        // MIME type checks
        $type = (preg_match('/image\/(.*)/', $mime)) ? 'image' : 'file';

        // Security file sign
        $sign                   = md5(Yii::$app->params['secretString'] . '_' . $resource_id . '_' . $type . '_' . Yii::$app->params['secretString']);
        $result = array(
            'success'   =>  true,
            'mime'      => $mime,
            'newUuid'   =>  $resource_id . '_' . $type . '_' . $sign,
        );
        return json_encode($result);
    }

    public function actionFilesDelete() {
        list($res_id, $res_type, $sign) = explode('_', $_POST['qquuid']);

        // Invalid signature - send error code
        if ($sign !== md5(Yii::$app->params['secretString'] . '_' . $res_id . '_' . $res_type . '_' . Yii::$app->params['secretString'])) {
            Yii::$app->getResponse()->setStatusCode(403);
        }

        /** @var Storage $storage */
        $storage = Yii::$app->getComponent('storage');
        if ($storage->delete($res_id)) {
            Yii::$app->getResponse()->setStatusCode(200);
        } else {
            Yii::$app->getResponse()->setStatusCode(503);
        }
    }

    protected function handleAttachment($body) {
        /** @var Storage $storage */
        $storage = Yii::$app->getComponent('storage');

        preg_match_all('/\[\[attachment(\d+)_([a-z]+)_([a-z0-9]+)\]\]/', $body, $attachments, PREG_SET_ORDER);

        foreach ($attachments as $attachment) {
            // Check sign
            list($full, $res_id, $res_type, $sign) = $attachment;

            // Invalid sign
            if ($sign !== md5(Yii::$app->params['secretString'] . '_' . $res_id . '_' . $res_type . '_' . Yii::$app->params['secretString'])) {
                continue;
            }

            switch ($res_type) {
                case 'image':
                    $body = str_replace($full, '<a href="' . $storage->link($res_id) . '" target="_blank"><img src=' . $storage->image($res_id) . ' border="0"></a>', $body);
                    break;
                default:
                    $body = str_replace($full, '<a href="' . $storage->link($res_id) . '" target="_blank">' . basename($storage->path($res_id)) . '</a>', $body);
                    break;
            }

        }

        return $body;
    }

}
