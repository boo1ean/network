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

}