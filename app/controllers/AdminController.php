<?php
namespace app\controllers;

use app\models\admin\InviteForm;
use app\models\admin\MainForm;
use app\models\admin\UserForm;
use Yii;
use yii\web\Controller;


class AdminController extends Controller
{
    public function beforeAction($action) {

        $authManager = Yii::$app->getComponent('authManager');
        $user        = Yii::$app->getUser()->getIdentity();

        // Check user on access
        if ($user === null || !$authManager->checkAccess($user->id, 'admin')) {
            Yii::$app->getResponse()->redirect('/');
            return false;
        }

        return parent::beforeAction($action);
    }

    public function actionMain() {
        $mainForm = new MainForm();
        $param      = array('model' => $mainForm);

        return $this->render('main', $param);
    }

    public function actionSendInvite() {
        $inviteForm = new InviteForm();
        $param      = array('model' => $inviteForm);

        if ($inviteForm->load($_POST)) {
            $param['message'] = $inviteForm->sendInvite();
        }

        return $this->render('sendInvite', $param);
    }

    /**
     * This is temporary function for simplify application testing
     */
    public function actionSendInviteTest() {
        $inviteForm = new InviteForm();
        $param      = array('model' => $inviteForm);

        if ($inviteForm->load($_POST)) {
            $param['message'] = $inviteForm->sendInviteTest();
        }

        return $this->render('sendInvite', $param);
    }

    public function actionUserEdit() {
        $userForm = new UserForm();
        $param    = array('model' => $userForm);

        if ($userForm->load($_POST)) {
            $param['message'] = $userForm->editUser();
        }

        return $this->render('userEdit', $param);
    }

    public function actionUserList() {
        $userForm  = new UserForm();
        $param     = array('model' => $userForm);

        if ($userForm->load($_POST)) {
            $param['message'] = $userForm->editUser();
        }

        return $this->render('userList', $param);
    }
}
