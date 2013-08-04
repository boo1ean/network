<?php

namespace app\controllers;

use app\components\Mailer;
use app\components\Queue;
use Yii;
use yii\web\Controller;

class TestController extends Controller
{

    public function actionMailerAsyncQueue() {
        /** @var Queue $queue */
        $queue = Yii::$app->getComponent('queue');
        $queue->enqueue('email', array(
            'to'    =>  'aivus@aivus.name',
            'subject'   =>  'Super subject',
            'body'      =>  'Message body',
        ));
    }

    public function actionMailerSyncQueue() {
        /** @var Queue $queue */
        $queue = Yii::$app->getComponent('queue');
        $queue->enqueue('email', array(
            'to'    =>  'aivus@aivus.name',
            'subject'   =>  'Super subject',
            'body'      =>  'Message body',
        ), false);
    }

    public function actionMailerNative() {
        /** @var Mailer $mail */
        $mail = Yii::$app->getComponent('mail');
        //$mail->setTo('aivus@aivus.name');
        $mail->addTo('aivus@aivus.name');
        $mail->addTo('admin@aivus.name');
        $mail->setSubject('Test message from binary-network');
        $mail->setBody('Test message');
        $sent = $mail->send();

        return 'Successfully sent ' . $sent . ' messages.';
    }
}