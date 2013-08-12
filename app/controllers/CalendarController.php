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
    static function calendarData() {
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

        return $events_json;
    }

    function actionCalendar() {
        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $events_json = self::calendarData();

        $id = Yii::$app->getUser()->getIdentity()->getId();
        $user = User::find($id);
        $gcal = $user->searchSetting('gcal_feed');

        return $this->render('calendar', array(
            'events_json' => $events_json,
            'gcal' => $gcal
        ));
    }

    function actionDropevent() {
        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $start_date = date("Y-m-d", strtotime($_POST['start']));
        $end_date = date("Y-m-d", strtotime($_POST['end']));

        $events = Event::findByTitle($_POST['title']);

        if ($events) {
            $events->start_date = $start_date;
            $events->end_date = $end_date;
            $events->save();
        }

        $events_json = self::calendarData();

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

    function actionEditEvent() {
        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $eventForm = new AddEventForm();
        $eventForm->scenario = 'default';

        $users = User::getAll();

        if (isset($_POST['event_id'])) {
            //event edit from events list
            $event = Event::find($_POST['event_id']);
            $id = $_POST['event_id'];
        } else if (isset($_POST['title'])) {
            //event edit from calendar
            $event = Event::findByTitle($_POST['title']);
            $id = $event->id;
        }

        $this->layout = 'block';

        return $this->render('editevent', array(
            'model' => $eventForm,
            'event' => $event,
            'users' => $users,
            'event_id' => $id
        ));
    }

    function actionSaveEvent() {
        if (!isset($_POST['id_event'])) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $eventForm = new AddEventForm();
        $eventForm->scenario = 'default';
        $eventForm->load($_POST);
        $eventForm->editEvent($_POST['id_event']);

        $status = count($eventForm->errors) > 0 ? 'error' : 'ok';

        $result = array(
            'status' => $status,
            'errors' => $eventForm->errors,
            'user'   => $eventForm->toArray()
        );
        echo json_encode($result);
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