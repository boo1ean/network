<?php
namespace app\controllers;

use app\models\LoginForm;
use app\models\RegistrationForm;
use app\models\EditProfileForm;
use app\models\SettingsForm;
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
     * @return view
     */
    public function actionLogin() {
        // Redirect for logged users.
        if(!Yii::$app->getUser()->getIsGuest())
            return Yii::$app->getResponse()->redirect('@www/');

        $loginForm = new LoginForm();

        if ($loginForm->load($_POST) && $loginForm->login()) {
            return Yii::$app->getResponse()->redirect('@www/');
        } else {
            return $this->render('login', array('model' => $loginForm));
        }
    }

    /**
     * edit user's profile
     * @return view
     */
    public function actionEdit() {
        $editProfileForm = new EditProfileForm();

        if ($editProfileForm->load($_POST) && $editProfileForm->save()) {
            return $this->render('edit', array('model' => $editProfileForm, 'message' => 'Well done! You successfully update your profile.'));
        } else {
            return $this->render('edit', array('model' => $editProfileForm));
        }
    }

    /**
     * registration users
     * @param string $email, when the user clicked on the link invitational
     * @param string $password_hash, when the user clicked on the link invitational
     * @return view
     */
    public function actionRegistration($email = '', $password_hash = '') {
        $registrationForm = new RegistrationForm();

        $isPost = $registrationForm->load($_POST);

        if(!$isPost && (empty($email) || empty($password_hash)))
            return $this->render('registration', array('message' => 'Sorry guy, registration only on invitation.'));

        $registrationForm->email = $email;
        $registrationForm->password_hash = $password_hash;

        if(!$isPost)
            $registrationForm->scenario = 'firstVisit';
        else
            $registrationForm->scenario = 'default';

        if ($isPost && $registrationForm->registration()) {
            return Yii::$app->getResponse()->redirect('@www/');
        } else {
            $registrationForm->validate();
            if(isset($registrationForm->errors['password_hash']) || isset($registrationForm->errors['email']))
                return $this->render('registration', array('message' => 'Nice try guy, but you can\'t be registered without invitation.'));
            else
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