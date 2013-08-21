<?php
$params = require(__DIR__ . '/params.php');
return array(
    'id'       => 'bootstrap',
    'basePath' => dirname(__DIR__),

    'preload'  => array(
        'eventHandler'
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
                '/'                                                         => 'user/all',
                '/admin'                                                    => 'admin/main',
                '/admin/library'                                            => 'admin/library-book-list',
                '/admin/library/<page:\d+>'                                 => 'admin/library-book-list',
                '/admin/user'                                               => 'admin/user-list',
                '/admin/user-list/<page:\d+>'                               => 'admin/user-list',
                '/auth/recover/<email:[^\s]+?>/<password_hash:[^\s]+>'      => 'auth/recover',
                '/auth/registration/<email:[^\s]+?>/<password_hash:[^\s]+>' => 'auth/registration',
                '/conversation/<id:\d+>'                                    => 'conversation',
                '/library/editbook/<id:\d+>'                                => 'library/editbook',
                '/calendar/eventpage/<id:\d+>'                              => 'calendar/eventpage'
            )
        ),

        'authManager' => array(
            'class' => 'app\components\AuthManager',
        ),

        'db' => require 'db.php',

        'mail' => array(
            'class'             => 'app\components\Mailer',
            'senderEmail'       => 'binary.academy.network@gmail.com',
            'transportType'     => 'smtp',
            'smtpHost'          => 'smtp.gmail.com',
            'smtpPort'          => '587',
            'smtpLogin'         => 'binary.academy.network@gmail.com',
            'smtpPassword'      => 'q3ofhgb2q3njgf02blvnf23i',
            'smtpEncryption'    => 'tls'
        ),

        'storage' => array(
            'class'     => 'app\components\Storage',
/*            'storageProvider' => 'app\components\storageProviders\LocalStorageProvider',
            'params' => array(
                'directory' => dirname(dirname(__DIR__)) . '/uploaded/',
            ),*/
            'storageProvider' => 'app\components\storageProviders\CloudStorageProvider',
            'params' => array(
                'accessToken'   => 'BEWxO0eSOZEAAAAAAAAAAQhEbkg7uUeN0lEtmFCnafFY04fzNVwlN875BB9SB3Im',
                'dropboxPath'   => '/'
            ),
        ),

        'queue' => array(
            'class'     => 'app\components\Queue',
            'servers'   => array('127.0.0.1' => 4730),         // array('127.0.0.1' => 12345, '127.0.0.2' => 12346)
            'sync'      => true,
        ),

        'queueWorker' => array(
            'class'         => 'app\components\QueueWorker',
            'servers'       => array('127.0.0.1' => 4730),         // array('127.0.0.1' => 12345, '127.0.0.2' => 12346)
            'jobClassPath'  => dirname(__DIR__) . '/jobs',
            'jobNamespace'  => 'app\jobs\\',
        ),

        'eventHandler'  =>  array(
            'class'     => 'app\components\EventHandler',
        ),


    ),

    'params' => $params
);
