<?php

namespace app\models;

use app\events\HandlerEvent;
use Yii;
use yii\base\Model;
use app\models\Event;
use app\models\User;

class AddEventForm extends Event
{

    public function rules() {
        return array(
            array('title, description, start_date, start_time, end_date, end_time, type', 'required'),
            array('end_date', 'validateDates'),
            array('end_time', 'validateTimes')
        );
    }

    public function scenarios() {
        return array(
            'default' => array('title', 'description', 'start_date', 'start_time', 'end_date', 'end_time', 'color'),
        );
    }

    public function validateDates() {
        if ($this->end_date < $this->start_date) {
            $this->addError('end_date', 'End date can\'t be earlier than start date');
        }
    }

    public function validateTimes() {
        if ($this->end_date == $this->start_date && $this->end_time < $this->start_time) {
            $this->addError('end_time', 'End time can\'t be earlier than start time');
        }
    }

    public function addEvent() {
        if ($this->validate()) {

            $event = new Event;

            $event->title       = $this->title;
            $event->description = $this->description;
            $event->start_date  = $this->start_date;
            $event->start_time  = $this->start_time;
            $event->end_date    = $this->end_date;
            $event->end_time    = $this->end_time;

            switch($_POST['type']) {
                case '0':
                    $event->type = self::TYPE_BIRTHDAY;
                    break;
                case '1':
                    $event->type = self::TYPE_CORPEVENT;
                    break;
                case '2':
                    $event->type = self::TYPE_HOLIDAY;
                    break;
                case '3':
                    $event->type = self::TYPE_DAYOFF;
                    break;
                default:
                    break;
            }

            $event->user_id = Yii::$app->getUser()->getId();
            $event->color   = $_POST['colorpicker'];
            $event->save();

            $user = User::find(Yii::$app->getUser()->getId());
            $event->link('users', $user);

            $usersId = array();
            if (isset($_POST['invitations'])) {
                $invites = $_POST['invitations'];

                foreach($invites as $id_user => $val) {
                    $user = User::findIdentity($id_user);
                    $event->link('users', $user);
                    $event->markAsUnread($user->id);
                    $usersId[] = $id_user;
                }
            }

            $this->sendNotifications($event->id, $usersId);

            return true;
        }

        return false;
    }

    public function editEvent($id) {
        if ($this->validate()) {

            /** @var Event $event */
            $event = Event::find($id);

            $event->title       = $this->title;
            $event->description = $this->description;
            $event->start_date  = $this->start_date;
            $event->start_time  = $this->start_time;
            $event->end_date    = $this->end_date;
            $event->end_time    = $this->end_time;

            switch($_POST['type']) {
                case '0':
                    $event->type = self::TYPE_BIRTHDAY;
                    break;
                case '1':
                    $event->type = self::TYPE_CORPEVENT;
                    break;
                case '2':
                    $event->type = self::TYPE_HOLIDAY;
                    break;
                case '3':
                    $event->type = self::TYPE_DAYOFF;
                    break;
                default:
                    break;
            }

            $users_new = isset($_POST['invitations']) ? $_POST['invitations'] : array();

            foreach ($event->users as $old) {

                if(0 == count($users_new)) {
                    $event->unlink('users', $old);
                } else {
                    foreach ($users_new as $id_new => $val) {

                        if($old->id == $id_new) {
                            unset($users_new[$id_new]);
                            break;
                        }

                        if ($old->id != $id_new && end($users_new) == $val) {
                            $event->unlink('users', $old);
                        }
                    }
                }
            }

            // Array of invited user ids
            $usersId = array();
            foreach ($users_new as $id_user => $val) {
                $user = User::findIdentity($id_user);
                $event->link('users', $user);
                $event->markAsUnread($user->id);
                $usersId[] = $id_user;
            }

            $event->user_id = Yii::$app->getUser()->getId();
            $event->color   = $_POST['colorpicker'];
            $event->save();

            $this->sendNotifications($event->id, $usersId);

            return true;
        }

        return false;
    }

    // Send event for notification
    protected function sendNotifications($eventId, $userIds) {
        $event = new HandlerEvent(array(
            'eventId'           =>      $eventId,
            'usersId'           =>      $userIds,
        ));
        Yii::$app->trigger('CALENDAR_USER_INVITE', $event);
    }
}