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

        'assetManager' => array(
            'bundles' => require(__DIR__ . '/assets.php')
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
                '/' => 'site/index'
            )
        ),

        'db' => require 'db.php'
    ),

    'params' => $params
);
