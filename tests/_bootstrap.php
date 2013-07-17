<?php
require(__DIR__ . '/../vendor/yiisoft/yii2/yii/Yii.php');
require(__DIR__ . '/../vendor/autoload.php');

$config = require(__DIR__ . '/../app/config/main.php');
$application = new yii\web\Application($config);
