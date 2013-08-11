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
     * @var boolean block or unblock user
     */
    public $is_block;

    /**
     * @var integer limit users on the one page
     */
    public $limit = 10;

    /**
     * @var integer what is the recording start
     */
    public $offset = 1;

    /**
     * @var string repeat_password for change password
     */
    public $repeat_password;

    /**
     * @return validation rules array
     */
    public function rules() {
        return array(
            array('email, first_name, last_name, id_edit, is_block', 'required'),
            array('email', 'email'),
            array('password', 'compare', 'compareAttribute' => 'repeat_password')
        );
    }

    /**
     * @return scenarios array
     */
    public function scenarios() {
        return array(
            'block'   => array('id_edit', 'is_block'),
            'default' => array('email', 'first_name', 'id_edit', 'last_name', 'password'),
            'only_id' => array('id_edit')
        );
    }

    /**
     * Block or unblock the user
     * @return mixed
     */
    public function userBlock() {
        if ($this->validate()) {
            $user = User::find($this->id_edit);
            $user->is_active = $this->is_block ? 0 : 1;
            $user->save();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete user
     * @return boolean
     */
    public function userDelete() {
        if ($this->validate()) {
            User::find($this->id_edit)->delete();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Load data for edit user
     * @return boolean
     */
    public function userEdit() {
        if ($this->validate()) {
            $user             = User::find($this->id_edit);

            $this->email      = $user->email;
            $this->first_name = $user->first_name;
            $this->last_name  = $user->last_name;
            return true;
        } else {
            return false;
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
            ->offset(($this->offset - 1) * $this->limit)
            ->orderBy('email')
            ->all();

        $count_total = $query_count->count();

        if ($this->limit < $count_total) {
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

    /**
     * Save data of user
     * @return boolean
     */
    public function userSave() {
        if ($this->validate()) {
            $user = User::find($this->id_edit);

            $user->email      = $this->email;
            $user->first_name = $this->first_name;
            $user->last_name  = $this->last_name;
            $user->password   = User::hashPassword($this->password);
            $user->save();

            return true;
        } else {
            return false;
        }
    }
}