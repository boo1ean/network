<?php

namespace app\controllers;

use app\models\CalendarSettingsForm;
use yii;
use yii\web\Controller;
use app\models\Event;
use app\models\Eventcomment;
use app\models\User;
use app\models\AddEventForm;

class CalendarController extends PjaxController
{
    static function calendarData() {

        $events = Event::sortByStartDate();

        foreach ($events as $event) {

            $users = $event->users;

            foreach($users as $user) {
                if ($user->id == Yii::$app->getUser()->getIdentity()->getId()) {
                    $events_array[] = array(
                        'id'          => $event->id,
                        'title'       => $event->title,
                        'start'       => $event->start_date.' '.$event->start_time,
                        'end'         => $event->end_date.' '.$event->end_time,
                        'color'       => $event->color,
                        'borderColor' => 'white',
                        'allDay'      => false
                    );
                    break;
                }
            }
        }

        if (isset($events_array)) {
            $events_json = json_encode($events_array);
        } else {
            $events_json = '';
        }

        return $events_json;
    }

    function actionCalendar() {

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

        date_default_timezone_set('Europe/Kiev');

        $start_date = date('Y-m-d', strtotime($_POST['start']));
        $start_time = date('H:i:s', strtotime($_POST['start']));
        $end_date = date('Y-m-d', strtotime($_POST['end']));
        $end_time = date('H:i:s', strtotime($_POST['end']));

        if (isset($_POST['id'])) {
            $events = Event::find($_POST['id']);
            $events->start_date = $start_date;
            $events->start_time = $start_time;
            $events->end_date = $end_date;
            $events->end_time = $end_time;
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

        $events = Event::sortByStartDateFromNow();

        if (isset($_POST['sel_filters'])) {
            $events = Event::filterByMultiType($_POST['sel_filters']);
        }
        $params = array();
        $params['events'] = $events;
        if (isset($_POST['filter_id'])) {
            $this->layout = 'block';
            return $this->renderPartial('events', $params);
        } else {
            $params['header'] = true;
            return $this->render('events', $params);
        }

    }

    function actionComment() {
        if (isset($_POST['event_id'])) {

            $event = Event::find($_POST['event_id']);

            $userId = Yii::$app->getUser()->getIdentity()->getId();

            $eventcomment = new Eventcomment;
            $eventcomment->user_id = $userId;
            $eventcomment->event_id = $_POST['event_id'];
            $eventcomment->body = $_POST['comment'];
            $eventcomment->save();
        }

        $this->layout = 'block';

        return $this->render('eventcomments', array(
            'event' => $event
        ));
    }

    function actionEventpage($id = null) {

        if ($id !== null) {
            $event = Event::find($id);
        } else if (isset($_POST['id'])) {
            $event = Event::find($_POST['id']);
        }

        if($event) {
            // Mark event as read
            $event->markAsRead(Yii::$app->getUser()->getIdentity()->id);
        }

        if ($id == null) {
            echo $event->id;
        } else if ($event) {
            return $this->render('eventpage', array(
                'event' => $event
            ));
        }
    }

    function actionAddevent() {

        date_default_timezone_set('Europe/Kiev');

        if (isset($_POST['start']) && isset($_POST['end'])) {
            $start_date = date('Y-m-d', $_POST['start']);
            $end_date   = date('Y-m-d', $_POST['end']);
        } else if (isset($_POST['date'])) {
            $start_date = date('Y-m-d', $_POST['date']);
            $end_date   = date('Y-m-d', $_POST['date']);
        } else {
            $start_date = date('Y-m-d');
            $end_date   = date('Y-m-d');
        }

        $eventForm = new AddEventForm();
        $eventForm->scenario = 'default';

        $this->layout = 'block';

        return $this->render('addevent', array(
            'model'      => $eventForm,
            'start_date' => $start_date,
            'end_date'   => $end_date
        ));
    }

    function actionEditEvent() {

        $eventForm = new AddEventForm();
        $eventForm->scenario = 'default';

        if (isset($_POST['event_id'])) {
            $event = Event::find($_POST['event_id']);
            $id    = $_POST['event_id'];
        }

        $this->layout = 'block';
        $user         = Yii::$app->getUser()->getIdentity();

        return $this->render('editevent', array(
            'model'      => $eventForm,
            'event'      => $event,
            'event_id'   => $id,
            'is_creator' => $event->user_id == $user->id,
            'members'    => $event->users,
            'user'       => $user
        ));
    }

    function actionSaveEvent() {

        $eventForm = new AddEventForm();
        $eventForm->scenario = 'default';
        $eventForm->load($_POST);

        if (isset($_POST['param'])) {
            $eventForm->addEvent();
        } else {
            $eventForm->editEvent($_POST['id_event']);
        }

        $status = count($eventForm->errors) > 0 ? 'error' : 'ok';

        $result = array(
            'status' => $status,
            'errors' => $eventForm->errors,
            'user'   => $eventForm->toArray()
        );
        echo json_encode($result);
    }

    function actionDeleteevent() {

        $event = Event::find($_POST['id']);
        $users = $event->users;

        foreach($users as $user) {
            $event->unlink('users', $user);
        }

        $event_comments = Eventcomment::byEvent($_POST['id']);

        foreach($event_comments as $comment) {
            $comment->delete();
        }

        $event->delete();
    }

    function actionSettings() {

        $id = Yii::$app->getUser()->getIdentity()->getId();
        $user = User::find($id);
        $gcal = $user->searchSetting('gcal_feed');

        if (isset($_POST['feed'])) {
            $user->addSetting('gcal_feed', $_POST['feed']);
            $user->save();

            $message = 'Settings have been saved';
            $feed = $_POST['feed'];
        } else {
            $message = null;
            $feed = $gcal;
        }

        return $this->render('settings', array(
            'message' => $message,
            'gcal' => $feed
        ));
    }

    public function actionMemberNotSubscribeList() {

        if (!Yii::$app->getRequest()->getIsAjax()) {
            return Yii::$app->getResponse()->redirect('calendar');
        }

        $event = isset($_POST['id_event']) && !empty($_POST['id_event']) ? Event::find($_POST['id_event']) : new Event();
        $users = array();

        foreach ($event->unsubscribedUsers as $user) {

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

}