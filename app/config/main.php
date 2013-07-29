<?php
$params = require(__DIR__ . '/params.php');
return array(
    'id'       => 'bootstrap',
    'basePath' => dirname(__DIR__),
    'preload'  => array('log', 'debug'),

    'modules' => array(
        'debug' => array(
            'class'   => 'yii\debug\Module',
            'enabled' => YII_DEBUG && YII_ENV === 'dev',
        ),
    ),

    'components' => array(
        'cache' => array(
            'class' => 'yii\caching\FileCache'
        ),

        'user' => array(
            'class'         => 'yii\web\User',
            'identityClass' => 'app\models\User'
        ),

        'log' => array(
            'targets' => array(
                array(
                    'class'  => 'yii\log\FileTarget',
                    'levels' => array('error', 'warning')
                )
            )
        ),

        'urlManager' => array(
            'enablePrettyUrl' => true,
            'baseUrl'         => '/',

            'rules' => array(
                '/'                                                         => 'site/index',
                '/admin'                                                    => 'admin/main',
                '/admin/user'                                               => 'admin/user-list',
                '/admin/user-list/<id:\d+>'                                 => 'admin/user-list',
                '/auth/registration/<email:[^\s]+?>/<password_hash:[^\s]+>' => 'auth/registration',
                '/message/conversation/<id:\d+>'                            => 'message/conversation',
                '/message/members/<id:\d+>'                                 => 'message/members',
                '/library/books/<id:\w+>'                                   => 'library/books',
                '/library/editbook/<id:\d+>'                                => 'library/editbook',
                '/library/deletebook/<id:\d+>'                              => 'library/deletebook',
                '/library/takebook/<id:\d+>'                                => 'library/takebook',
                '/library/books/<id:\d+>'                                   => 'library/books/'
            )
        ),

        'authManager' => array(
            'class' => 'app\components\AuthManager',
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
        ),

        'queue' => array(
            'class'     => 'app\components\Queue',
            'servers'   => array(),         // array('127.0.0.1' => 12345, '127.0.0.2' => 12346)
            'sync'      => true,
        ),
    ),

    'params' => $params
);
