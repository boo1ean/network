<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;
use app\models\User;

class NotificationController extends Controller
{
    public function beforeAction($action) {
        // Check user on access
        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('/');
            return false;
        }
        return true;
    }

    public function actionIndex(){
        /**
         * @var User $user current user
         */
        $user = Yii::$app->getUser()->getIdentity();
        $viewParameters = array();
        // Get all user's notifications
        $notifications = $user->notifications;
        foreach ($notifications as $notification) {
            // Current notification data
            $row = array();
            // Get type of notification
            $class = get_class($notification);
            switch ($class) {
                case 'app\models\Conversation':
                    $row['icon'] = 'icon-envelope';
                    $row['link'] = '/message/conversation/' . $notification->id;
                    $row['title'] = $notification->title;
                    $row['description'] = 'Last message was sent on ' . $notification->lastMessageTime;
                    break;
            }
            $viewParameters[] = $row;
        }
        //var_dump($viewParameters); die();
        return $this->render('all', array(
            'notifications' => $viewParameters,
        ));
    }
}