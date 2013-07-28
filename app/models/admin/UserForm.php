<?php

namespace app\models\admin;

use Yii;
use yii\base\Model;
use yii\data\Pagination;
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
        $current     = Yii::$app->getUser()->getIdentity()->getId();
        $query       = $this->find()->where('id <> '.$current);
        $query_count = clone $query;

        $users = $query->limit($this->limit)
            ->offset($this->offset)
            ->all();

        $pagination = new Pagination(array(
            'pageSize'   => $this->limit,
            'totalCount' => $query_count->count()
        ));

        return array(
            'pagination' => $pagination,
            'users'      => $users
        );
    }
}