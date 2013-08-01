<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Event;

class AddEventForm extends Event
{
    public function rules() {
        return array(
            //array('start_date, start_time, end_date, end_time, type', 'required'),
        );
    }

    public function addEvent() {
        if ($this->validate()) {

            $event = new Event;

            $event->start_date = $_POST['start_date'];
            $event->start_time = $_POST['start_time'];
            $event->end_date = $_POST['end_date'];
            $event->end_time = $_POST['end_time'];
            //$event->type = self::TYPE_PAPER;
            //$event->title = $this->description;
            //$event->description = $this->description;
            $event->user_id = Yii::$app->getUser()->getId();
            $event->save();
            return true;
        }

        return false;
    }
}