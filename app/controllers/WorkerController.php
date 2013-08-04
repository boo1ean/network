<?php

namespace app\controllers;

use app\components\QueueWorker;
use yii\console\Controller;

class WorkerController extends Controller
{

    public $defaultAction = 'start';

    public function actionStart() {
        /** @var QueueWorker $worker */
        $worker = \Yii::$app->getComponent('queueWorker');
        $worker->register();
        $worker->start();
    }
}