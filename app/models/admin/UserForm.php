<?php

namespace app\models\admin;

use Yii;
use yii\base\Model;
use app\models\User;

class UserForm extends User
{

    /**
     * Editing data of user
     * @return mixed
     */
    public function userEdit() {
        if ($this->validate()) {

        }

        return false;
    }

    /**
     * List of users
     * @return array
     */
    public function userList() {
        if ($this->validate()) {

        }

        return array();
    }
}