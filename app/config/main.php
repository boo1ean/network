<?php
$params = require(__DIR__ . '/params.php');
return array(
    'id'       => 'bootstrap',
    'basePath' => dirname(__DIR__),
    'preload'  => array('log'),

    'components' => array(
        'cache' => array(
            'class' => 'yii\caching\FileCache'
        ),

        'user' => array(
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User'
        ),

        'log' => array(
            'targets' => array(
                array(
                    'class' => 'yii\log\FileTarget',
                    'levels' => array('error', 'warning')
                )
            )
        ),

        'urlManager' => array(
            'enablePrettyUrl' => true,

            'rules' => array(
                '/' => 'site/index',
                '/auth/login/<email:[^\s]+>/<password_hash:[^\s]+>' => 'auth/login',
                '/message/conversation/<id:\d+>' => 'message/conversation'
            )
        ),

        'db' => require 'db.php',

        'mail' => array(
            'class'             => 'app\components\Mailer',
            'senderEmail'       => 'binary-network@aivus.name',
            'transportType'     => 'smtp',
            'smtpHost'          => 'smtp.gmail.com',
            'smtpPort'          => '587',
            'smtpLogin'         => 'binary-network@aivus.name',
            'smtpPassword'      => 'nf38i2949g2obngp',
            'smtpEncryption'    => 'tls'
        )
    ),

    'params' => $params
);
