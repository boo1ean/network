<?php

namespace app\models\admin;

use Yii;
use yii\base\Model;
use app\models\User;

class UserForm extends User
{
    /**
     * @var integer limit users on the one page
     */
    public $limit = 10;

    /**
     * @var integer what is the recording start
     */
    public $offset = 0;

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
        $current = Yii::$app->getUser()->getIdentity()->getId();
        $users = $this->find()
            ->where('id <> '.$current)
            ->limit($this->limit)
            ->offset($this->offset)
            ->all();

        $count_total = $this->find()
            ->where('id <> '.$current)
            ->count();

        return array(
            'count_total' => $count_total,
            'users'       => $users
        );
    }
}