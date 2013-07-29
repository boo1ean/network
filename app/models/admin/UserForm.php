<?php

namespace app\models\admin;

use Yii;
use yii\base\Model;
use yii\data\Pagination;
use app\models\User;

class UserForm extends User
{
    /**
     * @var integer user ID to be edited
     */
    public $id_edit;

    /**
     * @var boolean is this first loading of data or is this data form edit
     */
    public $is_first;

    /**
     * @var integer limit users on the one page
     */
    public $limit = 10;

    /**
     * @var integer what is the recording start
     */
    public $offset = 0;

    /**
     * @var string repeat_password for change password
     */
    public $repeat_password;

    /**
     * @var
     */
    public $user;

    /**
     * @return validation rules array
     */
    public function rules() {
        return array(
            array('email', 'required'),
            array('email', 'email'),
            array('repeat_password', 'compare', 'compareAttribute'=>'password')
        );
    }

    /**
     * Editing data of user
     * @return mixed
     */
    public function userEdit() {
        if($this->is_first) {
            return User::find($this->id_edit);
        } elseif ($this->validate()) {
            $user = User::find($this->id_edit);

            $user->email      = $this->email;
            $user->first_name = $this->first_name;
            $user->last_name  = $this->last_name;
            $user->password   = $this->hashPassword($this->password);
            $user->save();
            return $user;
        } else {
            return $this->errors;
        }
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

        $count_total = $query_count->count();

        if($this->limit < $count_total) {
            $pagination = new Pagination(array(
                'pageSize'   => $this->limit,
                'totalCount' => $query_count->count()
            ));
        } else {
            $pagination = null;
        }

        return array(
            'pagination' => $pagination,
            'users'      => $users
        );
    }
}