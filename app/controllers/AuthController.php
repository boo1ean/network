<?php
namespace app\controllers;

use app\models\LoginForm;
use app\models\RegistrationForm;
use Yii;
use yii\web\Controller;

class AuthController extends Controller
{
    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'yii\web\CaptchaAction',
            ),
        );
    }


    public function actionLogin() {

        // Redirect for logged users.
        if(!Yii::$app->getUser()->getIsGuest()) {
            return Yii::$app->getResponse()->redirect('site/index');
        }

        $loginForm = new LoginForm();
        if ($loginForm->load($_POST) && $loginForm->login()) {
            return Yii::$app->getResponse()->redirect('site/index');
        } else {
            return $this->render('login', array('model' => $loginForm));
        }
    }

    public function actionRegistration() {
        $registrationForm = new RegistrationForm();

        if ($registrationForm->load($_POST) && $registrationForm->registration()) {
            return Yii::$app->getResponse()->redirect('site/index');
        } else {
            return $this->render('registration', array('model' => $registrationForm));
        }
    }

    public function actionIndex() {
        return Yii::$app->getResponse()->redirect('site/index');
    }

    public function actionLogout() {
        Yii::$app->getUser()->logout();
        return Yii::$app->getResponse()->redirect('site/index');
    }
}