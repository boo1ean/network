<?php
namespace app\controllers;

use app\models\LoginForm;
use Yii;
use yii\web\Controller;

class AuthController extends Controller
{
/*    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'yii\web\CaptchaAction',
            ),
        );
    }*/


    public function actionLogin() {
        $loginForm = new LoginForm();

        if ($loginForm->load($_POST) && $loginForm->login()) {
            return Yii::$app->getResponse()->redirect('site/index');
        } else {
            return $this->render('login', array('model' => $loginForm));
        }
    }

    public function actionIndex() {
        return Yii::$app->getResponse()->redirect('site/index');
    }
}