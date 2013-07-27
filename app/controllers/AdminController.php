<?php
namespace app\controllers;

use app\models\AdminForm;
use Yii;
use yii\web\Controller;


class AdminController extends Controller
{
    public function beforeAction($action) {

        $authManager = Yii::$app->getComponent('authManager');
        $user = Yii::$app->getUser()->getIdentity();

        // Check user on access
        if ($user === null || !$authManager->checkAccess($user->id, 'admin')) {
            Yii::$app->getResponse()->redirect('/');
            return false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex() {
        // todo: open some administrate page
        Yii::$app->getResponse()->redirect('/admin/send-invite');
    }

    public function actionEditUser() {
        $adminForm = new AdminForm();
        $param     = array('model' => $adminForm);

        if ($adminForm->load($_POST)) {
            $param['message'] = $adminForm->editUser();
        }

        return $this->render('editUser', $param);
    }

    public function actionSendInvite() {
        $adminForm = new AdminForm();
        $param     = array('model' => $adminForm);

        if ($adminForm->load($_POST)) {
            $param['message'] = $adminForm->sendInvite();
        }

        return $this->render('sendInvite', $param);
    }

    /**
     * This is temporary function for simplify application testing
     */
    public function actionSendInviteTest() {
        $adminForm = new AdminForm();
        $param     = array('model' => $adminForm);

        if ($adminForm->load($_POST)) {
            $param['message'] = $adminForm->sendInviteTest();
        }

        return $this->render('sendInvite', $param);
    }
}
