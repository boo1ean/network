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
    public $notifications;

    public function init() {
        if ($this->avatar == null) {
            throw new InvalidParamException('Avatar was not found!');
        }
        if ($this->username == null) {
            throw new InvalidParamException('Username was not found!');
        }
        if ($this->notifications == null) {
            throw new InvalidParamException('Notifications were not found!');
        }
    }

    public function run() {

        $notificationsCount = count($this->notifications);
        $blinkClass = '';
        if ($notificationsCount > 0) {
            $blinkClass .= 'flashing';
        }
        // Container for userbox
        $html = Html::beginTag('ul', array(
            'id'    => 'userBox',
            'class' => 'nav pull-right',
        ));

        // Notifications
        $html .= Html::beginTag('li', array('class' => 'dropdown', 'id' => 'notifications'));

        $html .= Html::beginTag('a', array('class' => $blinkClass));
        $html .= $notificationsCount;
        $html .= Html::beginTag('i', array('class' => 'icon-envelope'));
        $html .= Html::endTag('i');
        $html .= Html::endTag('a');
        /*$html .= Html::beginTag('ul', array(
            'class' => 'dropdown',
        ));
        $html .= Html::tag('li', 'aaaa');
        $html .= Html::tag('li', 'aaaa');
        $html .= Html::endTag('ul');*/
        $html .= Html::endTag('li');

        // User info
        $html .= Html::beginTag('li');
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

        // Link to edit profile
        $html .= Html::beginTag('a', array('href' => '/auth/edit'));
        $html .= Html::beginTag('i', array('class' => 'icon-pencil'));
        $html .= Html::endTag('i');
        $html .= Html::endTag('a');

        // Link to logout
        $html .= Html::beginTag('a', array('href' => '/auth/logout'));
        $html .= Html::beginTag('i', array('class' => 'icon-share-alt'));
        $html .= Html::endTag('i');
        $html .= Html::endTag('a');
        $html .= Html::endTag('li');
        $html .= Html::endTag('ul');

        echo $html;
    }
}