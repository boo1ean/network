<?php
namespace app\controllers;

use app\models\AdminForm;
use Yii;
use yii\web\Controller;


class AdminController extends Controller
{
    public function actionIndex()
    {
        $adminForm = new AdminForm();
        $param = array('model' => $adminForm);
        if ($adminForm->load($_POST))
            $param['message'] = $adminForm->sendInvite();

        return $this->render('sendInvite',$param);
    }
}