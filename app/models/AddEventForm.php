<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Event;

class AddEventForm extends Event
{
    public function rules() {
        return array(
            array('start_date, start_time, end_date, end_time, type', 'required'),
        );
    }

    public function scenarios() {
        return array(
            'default' => array('title', 'description', 'start_date', 'start_time', 'end_date', 'end_time'),
        );
    }

    public function addEvent() {
        if ($this->validate()) {

            $event = new Event;

            $event->start_date = $this->start_date;
            $event->start_time = $this->start_time;
            $event->end_date = $this->end_date;
            $event->end_time = $this->end_time;
            //$event->type = self::TYPE_PAPER;
            $event->title = $this->title;
            $event->description = $this->description;
            $event->user_id = Yii::$app->getUser()->getId();
            $event->save();
            return true;
        }

        return false;
    }

    public function editEvent($id) {
        if ($this->validate()) {

            $event = Event::find($id);

            $event->start_date = $this->start_date;
            $event->start_time = $this->start_time;
            $event->end_date = $this->end_date;
            $event->end_time = $this->end_time;
            //$event->type = self::TYPE_PAPER;
            $event->title = $this->title;
            $event->description = $this->description;
            $event->user_id = Yii::$app->getUser()->getId();
            $event->save();
            return true;
        }

        return false;
    }
}