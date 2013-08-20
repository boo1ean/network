<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Event;
use app\models\User;
use app\models\Userevent;

class AddEventForm extends Event
{
    const TYPE_BIRTHDAY = 0;
    const TYPE_CORPEVENT = 1;
    const TYPE_HOLIDAY = 2;
    const TYPE_DAYOFF = 3;

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

            $event->title = $this->title;
            $event->description = $this->description;
            $event->start_date = $this->start_date;
            $event->start_time = $this->start_time;
            $event->end_date = $this->end_date;
            $event->end_time = $this->end_time;

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
            $event->create_datetime = gmdate('Y-m-d H:i:s', time()+10800);
            $event->color = $_POST['colorpicker'];
            $event->save();

            if (isset($_POST['invitations'])) {
                $invites = $_POST['invitations'];

                foreach($invites as $invite) {
                    $user = User::findByEmail($invite);

                    $userevent = new Userevent();
                    $userevent->event_id = $event->id;
                    $userevent->user_id = $user->id;
                    $userevent->unread = 1;
                    $userevent->save();
                }

                $userevent = new Userevent();
                $userevent->event_id = $event->id;
                $userevent->user_id = Yii::$app->getUser()->getId();
                $userevent->unread = 0;
                $userevent->save();
            }

            return true;
        }

        return false;
    }

    public function editEvent($id) {
        if ($this->validate()) {

            $event = Event::find($id);

            $event->title = $this->title;
            $event->description = $this->description;
            $event->start_date = $this->start_date;
            $event->start_time = $this->start_time;
            $event->end_date = $this->end_date;
            $event->end_time = $this->end_time;

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
            $event->color = $_POST['colorpicker'];
            $event->save();

            return true;
        }

        return false;
    }
}