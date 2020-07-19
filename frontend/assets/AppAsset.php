<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "https://fonts.googleapis.com/css?family=Cormorant+Garamond:400,700|Roboto:300,400,700&amp;subset=cyrillic",
        'css/style.css',
    ];
    public $js = [
        'js/isotope.pkgd.min.js',
        'js/slick.min.js',
        'js/orphus.js',
        'js/jquery-ias.min.js',
        'js/script.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
