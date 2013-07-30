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
     * @return validation rules array
     */
    public function rules() {
        return array(
            array('email, id_edit, is_block, is_first', 'required'),
            array('email', 'email'),
            array('password', 'compare', 'compareAttribute'=>'repeat_password')
        );
    }

    /**
     * @return scenarios array
     */
    public function scenarios() {
        return array(
            'block'   => array('id_edit', 'is_block'),
            'default' => array('email', 'id_edit', 'is_first', 'password'),
            'isFirst' => array('id_edit', 'is_first')
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
     * Editing data of user
     * @return mixed
     */
    public function userEdit() {
        if ($this->validate()) {
            if($this->is_first) {
                $user = User::find($this->id_edit);
                $this->email      = $user->email;
                $this->first_name = $user->first_name;
                $this->last_name  = $user->last_name;
                return true;
            } else {
                $user = User::find($this->id_edit);

                $user->email      = $this->email;
                $user->first_name = $this->first_name;
                $user->last_name  = $this->last_name;
                $user->password   = User::hashPassword($this->password);
                $user->save();
                return true;
            }
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