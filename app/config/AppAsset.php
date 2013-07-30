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
        'js/fancybox/source/jquery.fancybox.css',
    );
    public $js = array(
        'js/admin/user.js',
        'js/fancybox/source/jquery.fancybox.js',
        'js/site.js'
    );
    public $depends = array(
        'yii\web\YiiAsset',
        'yii\bootstrap\ResponsiveAsset',
    );
}