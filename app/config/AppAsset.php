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
        'css/toastr.css',
        'css/site.css',
        'css/bootstrap-glyphicons.css',
        'css/jquery.tagsinput.css',
        'css/fullcalendar.css',
        'css/fullcalendar.print.css',


    );
    public $js = array(
        'js/jquery-2.0.3.min.js',
        'js/toastr.js',
        'js/bootstrap.min.js',
        'js/library/books.js',
        'js/calendar/events.js',
        'js/jquery.tagsinput.js',
        'js/calendar/jquery-ui-1.10.3.custom.min.js',
        'js/calendar/fullcalendar.min.js',
        'js/calendar/gcal.js',
        'js/calendar/show-calendar.js',
        'js/admin/ajaxuploader.js',
        'js/admin/user.js',
        'js/admin/book.js',
        'js/pjax/jquery.pjax.js',
        'js/pjax/jquery.cookie.js',
        'js/pjax/pjax-navigation.js',

        'js/site.js'

    );
    public $depends = array(
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    );
}