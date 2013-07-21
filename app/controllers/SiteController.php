<?php namespace app\controllers;

use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actionIndex() {
        return $this->render('index');
    }

    public  function actionMailer()
    {
        $mail = Yii::$app->getComponent('mail');
        //$mail->setTo('aivus@aivus.name');
        $mail->addTo('aivus@aivus.name');
        $mail->addTo('admin@aivus.name');
        $mail->setFrom('binary-network@aivus.name');
        $mail->setSubject('Test message from binary-network');
        $mail->setBody('Test message');
        $sent = $mail->send();

        return 'Successfully sent ' . $sent . ' messages.';
    }
}
