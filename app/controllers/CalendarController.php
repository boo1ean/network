<?php

namespace app\controllers;

use app\models\CalendarSettingsForm;
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
                'title'  => $event->title,
                'start'  => $event->start_date.' '.$event->start_time,
                'end'    => $event->end_date.' '.$event->end_time,
                'allDay' => false,
            );
        }

        if (isset($events_array)) {
            $events_json = json_encode($events_array);
        } else {
            $events_json = '';
        }

        $id = Yii::$app->getUser()->getIdentity()->getId();
        $user = User::find($id);
        $gcal = $user->searchSetting('gcal_feed');

        return $this->render('calendar', array(
            'events_json' => $events_json,
            'gcal' => $gcal
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
            Yii::$app->getResponse()->redirect('@web/calendar/calendar');
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

    function actionSettings() {
        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $id = Yii::$app->getUser()->getIdentity()->getId();
        $user = User::find($id);
        $gcal = $user->searchSetting('gcal_feed');

        $calendarSettingsForm = new CalendarSettingsForm();

        if (isset($_POST['feed']) && $calendarSettingsForm->saveSettings()) {
            return $this->render('settings', array(
                'message' => 'Settings have been saved',
                'gcal' => $_POST['feed']
            ));
        } else {
            return $this->render('settings', array(
                'gcal' => $gcal
            ));
        }
    }
}