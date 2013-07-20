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

    /**
     * logging in users
     * @param string $email, when the user clicked on the link invitational
     * @param string $password_hash, when the user clicked on the link invitational
     * @return view
     */
    public function actionLogin($email = '', $password_hash = '') {
        // Redirect for logged users.
        if(!Yii::$app->getUser()->getIsGuest())
            return Yii::$app->getResponse()->redirect('@www/');

        $loginForm = new LoginForm();
        $isGet = !empty($email) && !empty($password_hash);
        if($isGet) {
            $loginForm->email = $email;
            $loginForm->password_hash = $password_hash;
            $loginForm->scenario = 'onInvite';
        }

        if (($isGet || $loginForm->load($_POST)) && $loginForm->login()) {
            return Yii::$app->getResponse()->redirect('@www/');
        } else {
            return $this->render('login', array('model' => $loginForm));
        }
    }

    public function actionRegistration() {
        $registrationForm = new RegistrationForm();

        if ($registrationForm->load($_POST) && $registrationForm->registration()) {
            return Yii::$app->getResponse()->redirect('@www/');
        } else {
            return $this->render('registration', array('model' => $registrationForm));
        }
    }

    public function actionIndex() {
        return Yii::$app->getResponse()->redirect('@www/');
    }

    public function actionLogout() {
        Yii::$app->getUser()->logout();
        return Yii::$app->getResponse()->redirect('@www/');
    }
}