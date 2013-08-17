<?php
namespace app\controllers;

use app\models\EditProfileForm;
use app\models\ForgotForm;
use app\models\LoginForm;
use app\models\RecoverForm;
use app\models\RegistrationForm;
use app\models\SettingsForm;
use Yii;
use yii\web\Controller;

class AuthController extends PjaxController
{
    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'yii\web\CaptchaAction',
            ),
        );
    }

    /**
     * edit user's profile
     * @return view
     */
    public function actionEdit() {
        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $editProfileForm = new EditProfileForm();

        if(!empty($_POST)) {
            $editProfileForm->load($_POST);
            $editProfileForm->notification = isset($_POST['notification']);
        }

        if (!empty($_POST) && $editProfileForm->saveProfile()) {
            return $this->render('edit', array(
                'model'   => $editProfileForm,
                'message' => 'Well done! You successfully update your profile.'
            ));
        } else {
            return $this->render('edit', array(
                'model'  => $editProfileForm
            ));
        }
    }

    public function actionForgot() {
        $this->layout = 'block';

        $forgotForm = new ForgotForm();
        $param      = array('model' => $forgotForm);

        if ($forgotForm->load($_POST)) {
            $param['message'] = $forgotForm->send();
        }

        return $this->render('forgot', $param);
    }

    public function actionForgotSave() {
        $result = array(
            'redirect' => Yii::$app->getUrlManager()->getBaseUrl(),
            'status'   => 'ok'
        );
        if (!Yii::$app->getRequest()->getIsAjax() || !Yii::$app->getRequest()->getIsPost()) {
            $result['status'] = 'redirect';
        }

        if('ok' == $result['status']) {
            $forgotForm = new ForgotForm();
            $forgotForm->email = $_POST['ForgotForm']['email'];

            $message = $forgotForm->send();
        }


        $result['status']  = count($forgotForm->errors) > 0 ? 'error' : $result['status'];
        $result['errors']  = $forgotForm->errors;
        $result['message'] = $message;

        echo json_encode($result);
    }

    public function actionIndex() {
        return Yii::$app->getResponse()->redirect('@web');
    }

    /**
     * logging in users
     * @return view
     */
    public function actionLogin() {
        // Redirect for logged users.
        if(!Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $loginForm = new LoginForm();

        if ($loginForm->load($_POST) && $loginForm->login()) {
            return Yii::$app->getResponse()->redirect('@web');
        } else {
            return $this->render('login', array('model' => $loginForm));
        }
    }

    public function actionLogout() {
        Yii::$app->getUser()->logout();
        return Yii::$app->getResponse()->redirect('@web');
    }

    /**
     * recover user password
     * @param string $email, when the user clicked on the link recover
     * @param string $password_hash, when the user clicked on the link recover
     * @return view
     */
    public function actionRecover($email = '', $password_hash = '') {
        $recoverForm = new RecoverForm();

        $isPost = $recoverForm->load($_POST);

        if (!$isPost && (empty($email) || empty($password_hash))) {
            return $this->render('recover', array(
                'message' => 'Don\'t do it hacker.'
            ));
        }

        $recoverForm->email         = $email;
        $recoverForm->password_hash = $password_hash;

        if (!$isPost) {
            $recoverForm->scenario = 'firstVisit';
        } else {
            $recoverForm->scenario = 'default';
        }

        if ($isPost && $recoverForm->recover()) {
            return Yii::$app->getResponse()->redirect('@web');
        } else {
            $recoverForm->validate();
            if (isset($recoverForm->errors['password_hash']) || isset($recoverForm->errors['email'])) {
                return $this->render('recover', array(
                    'message' => 'Don\'t do it hacker.'
                ));
            } else {
                return $this->render('recover', array('model' => $recoverForm));
            }
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

        if (!$isPost && (empty($email) || empty($password_hash))) {
            return $this->render('registration', array(
                'message' => 'Sorry guy, registration only on invitation.'
            ));
        }

        $registrationForm->email         = $email;
        $registrationForm->password_hash = $password_hash;

        if (!$isPost) {
            $registrationForm->scenario = 'firstVisit';
        } else {
            $registrationForm->scenario = 'default';
        }

        if ($isPost && $registrationForm->registration()) {
            return Yii::$app->getResponse()->redirect('@web');
        } else {
            $registrationForm->validate();
            if (isset($registrationForm->errors['password_hash']) || isset($registrationForm->errors['email'])) {
                return $this->render('registration', array(
                    'message' => 'Nice try guy, but you can\'t be registered without invitation.'
                ));
            } else {
                return $this->render('registration', array('model' => $registrationForm));
            }
        }
    }
}
