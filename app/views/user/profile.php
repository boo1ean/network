<?php
use yii\helpers\Html;
use app\helpers\DateTimeHelper;
?>

    <div id = "user-profile">
        <?php
        // Image div
        echo Html::beginTag('div', array('id' => 'user-profile-img'));
        echo Html::img($user->getAvatar(null, 150), array(
            'width'  => '150',
            'height' => '150',
            'class'  => 'img-rounded'
        ));
        echo Html::endTag('div');
        // Info div
        echo Html::beginTag('div', array('id' => 'user-profile-info'));
        // Username
        echo Html::beginTag('div');
        echo Html::tag('span', $user->userName, array('id' => 'user-profile-name'));
        // Online or not
        if($user->isOnline) {
            $lastActivity = 'online';
        } else {
            $lastActivity = DateTimeHelper::relativeTime($user->last_activity);
        }
        echo Html::tag('span', $lastActivity, array(
            'id'    => 'user-profile-activity'
        ));
        echo Html::endTag('div');
        // Link to email
        echo Html::tag('a', $user->email, array(
            'id' => 'user-profile-email',
            'href' => 'mailto:' . $user->email));
        echo Html::tag('br');
        echo Html::tag('br');
        // Is user blocked
        if (!$user->is_active) {
            echo Html::tag('p', 'User is blocked', array('id' => 'user-profile-blocked'));
        }
        // Button for sending private messages
        echo Html::tag('a', 'Send message', array(
            'class' => 'btn btn-info',
            'id'    => 'user-profile-message',
            'href'  => '/conversation/private/' . $user->id
        ));
        echo Html::endTag('div');
        ?>
    </div>