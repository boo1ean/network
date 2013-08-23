<?php

namespace app\controllers;
use yii;
use app\models\User;
use yii\db\Query;


class UserController extends PjaxController
{
    public function actionIndex() {
        return Yii::$app->getResponse()->redirect('user/list');
    }

    public function actionList($filter = 'all') {
        if ($filter != 'all' && $filter != 'online') {
            return Yii::$app->getResponse()->redirect('user/list');
        } else if($filter == 'all') {
            $users = User::getAllUsers();
        } else {
            $users = User::getOnlineUsers();
        }

        $param = array('users' => $users);
        return $this->render('usersList', $param);
    }

    public function actionProfile($id = null) {
        if($id == null) {
            return Yii::$app->getResponse()->redirect('user/list');
        }
        $user = User::find($id);
        if($user == null) {
            return Yii::$app->getResponse()->redirect('user/list');
        }
        $param = array('user' => $user);

        return $this->render('profile', $param);
    }

}