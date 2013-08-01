<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\Event;
use app\models\User;
use app\models\AddEventForm;

class CalendarController extends Controller
{
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
}