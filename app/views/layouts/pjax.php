<?php
use app\widgets\UserBox;

if(!Yii::$app->getUser()->getIsGuest()) {
    echo UserBox::widget(array(
        'avatar'              => Yii::$app->getUser()->getIdentity()->avatar,
        'username'            => Yii::$app->getUser()->getIdentity()->userName,
        'notificationsCount'  => Yii::$app->getUser()->getIdentity()->notificationsCount,
    ));
}

echo $content;