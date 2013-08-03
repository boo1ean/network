<?php
namespace app\controllers;

use app\models\User;
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

    public function actionUserBlock() {

        if(!isset($_POST['id_edit']) || !isset($_POST['is_block'])){
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $userForm = new UserForm();

        $userForm->id_edit  = $_POST['id_edit'];
        $userForm->is_block = $_POST['is_block'];
        $userForm->scenario = 'block';

        $userForm->userBlock();
    }

    public function actionUserEdit() {

        if(!isset($_POST['id_edit'])){
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $userForm = new UserForm();

        $userForm->id_edit  = $_POST['id_edit'];
        $userForm->scenario = 'load';

        $userForm->userEdit();

        $this->layout = 'block';
        $param = array('model' => $userForm);
        return $this->render('userEdit', $param);
    }

    public function actionUserList($page = 0) {

        $userForm = new UserForm();
        $userForm->offset = $page;

        $users_data = $userForm->userList();
        $users      = array();

        foreach($users_data['users'] as $key => $user) {
            $users[$key]['avatar'] = $user->getAvatar();
            $users[$key] = $user;
        }

        $param = array(
            'model'      => $userForm,
            'pagination' => $users_data['pagination'],
            'users'      => $users
        );

        return $this->render('userList', $param);
    }

    public function actionUserSave() {

        if(!isset($_POST['id_edit'])){
            Yii::$app->getResponse()->redirect('@web');
            return false;
        }

        $userForm = new UserForm();

        $userForm->email           = $_POST['UserForm']['email'];
        $userForm->first_name      = $_POST['UserForm']['first_name'];
        $userForm->id_edit         = $_POST['id_edit'];
        $userForm->last_name       = $_POST['UserForm']['last_name'];
        $userForm->password        = $_POST['UserForm']['password'];
        $userForm->repeat_password = $_POST['UserForm']['repeat_password'];

        $userForm->userSave();

        $status = count($userForm->errors) > 0 ? 'error' : 'ok';

        $result = array(
            'status' => $status,
            'errors' => $userForm->errors,
            'user'   => $userForm->toArray()
        );
        echo json_encode($result);
    }
}
