<?php

namespace app\controllers;
use yii;
use app\models\User;
use yii\db\Query;


class UserController extends PjaxController
{
    public function beforeAction($action) {

        // Check user on access
        if (Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getResponse()->redirect('/');
            return false;
        }return parent::beforeAction($action);
    }

    public function actionIndex() {
        $this->actionAll();
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