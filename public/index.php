<?php
// comment out the following line to disable debug mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/yiisoft/yii2/yii/Yii.php');
require(__DIR__ . '/../vendor/autoload.php');

$config = require(__DIR__ . '/../app/config/main.php');

$application = new yii\web\Application($config);
$application->run();
