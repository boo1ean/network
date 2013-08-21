<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\base\InvalidParamException;
use app\helpers\DateTimeHelper;

class NotificationController extends PjaxController
{
    /**
     * Render all user notifications in static page
     * @return string
     */
    public function actionIndex(){

        $viewParameters = $this->getNotifications();
        return $this->render('all', array(
            'notifications' => $viewParameters,
        ));
    }

    /**
     * Return json object contains notification for ajax request
     * Redirect for not ajax
     * @return bool|string
     */
    public function actionJson() {
        if (Yii::$app->getRequest()->getIsAjax()) {
            return json_encode($this->getNotifications(5));
        } else {
            Yii::$app->getResponse()->redirect('/');
            return false;
        }
    }

    /**
     * Return specified count of notifications
     * If count is not set, return all
     * @param null $count of notifications to return
     * @return array notifications' data (icon, link, title, description)
     */
    public function getNotifications($count = null) {
        /**
         * @var User $user current user
         */
        $user = Yii::$app->getUser()->getIdentity();
        $result = array();
        // Get all user's notifications
        $notifications = $user->notifications;

        // Processing all notifications depends on theirs class
        for ($i = 0; $i < count($notifications); $i++) {
            // Current notification data
            $row = array();
            // Get type of notification
            $class = get_class($notifications[$i]);
            switch ($class) {
                case 'app\models\Conversation':
                    $time = $notifications[$i]->lastMessageTime;
                    $row['icon'] = 'glyphicon glyphicon-envelope';
                    $row['link'] = '/conversation/' . $notifications[$i]->id;
                    $row['title'] = $notifications[$i]->title;
                    $row['description'] = 'Last message was sent ' . DateTimeHelper::relativeTime($time);
                    $row['time'] = $time;
                    break;
                case 'app\models\Event':
                    $time = $notifications[$i]->create_datetime;
                    $row['icon'] = 'glyphicon glyphicon-calendar';
                    $row['link'] = '/calendar/eventpage/' . $notifications[$i]->id;
                    $row['title'] = $notifications[$i]->title;
                    $row['description'] = 'Event starts ' . DateTimeHelper::relativeTime($notifications[$i]->start_date);
                    $row['time'] = $time;
                    break;
                default:
                    throw new InvalidParamException("Unknown notification class: " . $class);
            }
            $result[] = $row;
        }

        // Sort notifications by time desc
        usort($result, function($a, $b){
            return ($a['time'] > $b['time']) ? -1 : 1;
        });

        // Number of notifications to return
        if ($count == null) {
            $notificationsCount = count($notifications);
        } else {
            $notificationsCount = min(count($notifications), $count);
        }

        return array_slice($result, 0, $notificationsCount);
    }

}