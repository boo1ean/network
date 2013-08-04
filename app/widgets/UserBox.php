<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sophie
 * Date: 04.08.13
 * Time: 21:21
 * To change this template use File | Settings | File Templates.
 */
namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\base\InvalidParamException;

class UserBox extends Widget
{
    /**
     * @var string link to image
     */
    public $avatar;
    /**
     * @var string name of user
     */
    public $username;

    public function init() {
        if ($this->avatar == null) {
            throw new InvalidParamException('Avatar was not found!');
        }
        if ($this->username == null) {
            throw new InvalidParamException('Username was not found!');
        }
    }

    public function run() {
        // Container for userbox
        $html = Html::beginTag('ul', array(
            'id'    => 'userBox',
            'class' => 'nav pull-right',
        ));

        $html .= Html::beginTag('li', array('class' => 'dropdown'));
        $html .= Html::beginTag('a', array('id' => 'userBoxName'));

        // Add user avatar
        $html .= Html::img($this->avatar, array(
            'class' => 'img-rounded',
            'id'    => 'userBoxAvatar',
            'width' => '20',
            'height'=> '20',
        ));
        // Add username
        $html .= $this->username;
        $html .= Html::endTag('a');

        // Dropdown
        $html .= Html::beginTag('ul', array(
            'class' => 'dropdown',
        ));
        // Edit btn
        $html .= Html::beginTag('li');
        $html .= Html::beginTag('i', array('class' => 'icon-pencil'));
        $html .= Html::endTag('i');
        $html .= Html::a('Edit Profile', '/auth/edit');
        $html .= Html::endTag('li');
        // Logout btn
        $html .= Html::beginTag('li');
        $html .= Html::beginTag('i', array('class' => 'icon-share-alt'));
        $html .= Html::endTag('i');
        $html .= Html::a('Logout', '/auth/logout');
        $html .= Html::endTag('li');

        $html .= Html::endTag('ul');
        $html .= Html::endTag('li');
        $html .= Html::endTag('ul');

        echo $html;
    }
}