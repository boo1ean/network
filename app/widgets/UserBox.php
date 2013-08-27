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
    /**
     * @var array of unread notifications
     */
    public $notificationsCount;
    /**
     * @var string link to profile
     */
    public $link;

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
        $html = Html::beginTag('div', array(
            'id'    => 'userBox',
        ));

        // Notifications
        if ($this->notificationsCount > 0) {
            $html .= Html::beginTag('a', array(
                'class' => 'flashing',
                'id'    => 'userBoxNotifications',
                'title' => 'Unread notifications',
               // 'href'  => '/notification'
            ));
            $html .= $this->notificationsCount;
            $html .= Html::beginTag('span', array('class' => 'glyphicon glyphicon-comment'));
            $html .= Html::endTag('span');
            $html .= Html::endTag('a');
        }

        // User info
        $html .= Html::beginTag('a', array('href' => $this->link));
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

        // Link to edit profile
        $html .= Html::beginTag('a', array(
            'href'  => '/auth/edit',
            'id'    => 'userBoxEdit',
            'title' => 'Edit profile'
        ));
        $html .= Html::beginTag('span', array('class' => 'glyphicon glyphicon-user'));
        $html .= Html::endTag('span');
        $html .= Html::endTag('a');

        // Link to logout
        $html .= Html::beginTag('a', array(
            'href' => '/auth/logout',
            'id'    => 'userBoxLogout',
            'title' => 'Logout'
        ));
        $html .= Html::beginTag('span', array('class' => 'glyphicon glyphicon-share-alt'));
        $html .= Html::endTag('span');
        $html .= Html::endTag('a');

        $html .= Html::endTag('div');

        echo $html;
    }
}