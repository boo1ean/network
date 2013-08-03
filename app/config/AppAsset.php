<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\config;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = array(
        'css/site.css',
        'css/jquery.tagsinput.css'
    );
    public $js = array(
        'js/admin/user.js',
        'js/bootstrap.min.js',
        'js/library/books.js',
        'js/calendar/events.js',
        'js/site.js',
        'js/jquery-2.0.3.min.js',
        'js/jquery.tagsinput.js'
    );
    public $depends = array(
        'yii\web\YiiAsset',
        'yii\bootstrap\ResponsiveAsset',
    );
}