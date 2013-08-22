<?php

namespace app\controllers;
use yii;
use app\models\User;
use yii\db\Query;


class UserController extends PjaxController
{
    public function actionIndex() {
        return Yii::$app->getResponse()->redirect('user/all');
    }

    public function actionAll() {
        // Get all users
        $users = User::find()
            ->orderBy(array('last_activity' => Query::SORT_DESC))
            ->all();

        $param = array('users' => $users);
        return $this->render('usersList', $param);
    }

    public function actionProfile($id = null) {
        if($id == null) {
            return Yii::$app->getResponse()->redirect('user/all');
        }
        $user = User::find($id);
        if($user == null) {
            return Yii::$app->getResponse()->redirect('user/all');
        }
        $param = array('user' => $user);

        return $this->render('profile', $param);
    }

}