<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\Event;
use app\models\User;
use app\models\AddEventForm;

class CalendarController extends Controller
{
    function actionCalendar() {
        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $events = Event::sortByStartDate();

        foreach ($events as $event) {
            $events_array[] = array(
                'title' => $event->title,
                'start' => $event->start_date.' '.$event->start_time,
                'end'   => $event->end_date.' '.$event->end_time,
            );
        }

        $events_json = json_encode($events_array);

        return $this->render('calendar', array(
            'events_json' => $events_json
        ));
    }

    function actionEvents() {
        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $events = Event::sortByStartDate();

        return $this->render('events', array(
            'events' => $events
        ));
    }

    function actionAddevent() {
        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $eventForm = new AddEventForm();
        $eventForm->scenario = 'default';

        $users = User::getAll();

        if ($eventForm->load($_POST) && $eventForm->addEvent()) {
            Yii::$app->getResponse()->redirect('@web/calendar/events');
        } else {
            return $this->render('addevent', array(
                'model' => $eventForm,
                'users' => $users
            ));
        }
    }

    function actionEditevent($id) {
        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $eventForm = new AddEventForm();
        $eventForm->scenario = 'default';

        $users = User::getAll();

        $event = Event::find($id);

        if ($eventForm->load($_POST) && $eventForm->editEvent($id)) {
            Yii::$app->getResponse()->redirect('@web/calendar/events');
        } else {
            return $this->render('editevent', array(
                'model' => $eventForm,
                'event' => $event,
                'users' => $users
            ));
        }
    }

    function actionEventsjson() {
        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        return $this->render('eventsjson', array(
            'events' => $_POST['events_json']
        ));
    }

    function actionDeleteevent() {
        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $event = Event::find($_POST['id']);

        $users = $event->users;

        foreach ($users as $user) {
            $user->unlink('events', $event);
        }

        $event->delete();

        return Yii::$app->getResponse()->redirect('@web/calendar/events');
    }
}