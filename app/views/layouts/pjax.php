<?php
use app\widgets\UserBox;

if(!Yii::$app->getUser()->getIsGuest()) {
    $user = Yii::$app->getUser()->getIdentity();
    echo UserBox::widget(array(
        'avatar'              => $user->avatar,
        'username'            => $user->userName,
        'notificationsCount'  => $user->notificationsCount,
        'link'                => '/user/profile/' . $user->id
    ));
}

echo $content;