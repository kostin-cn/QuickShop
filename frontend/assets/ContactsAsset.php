<?php
/**
 * Created by PhpStorm.
 * User: Hexagen
 * Date: 16.12.2016
 * Time: 7:08
 */

namespace frontend\assets;
use yii\web\AssetBundle;

class ContactsAsset extends AssetBundle
{
    public $sourcePath = '@webroot/js';
    public $js = [
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyBV8b1METnx_Vv9YuO6JIjGJjKomdI7qZQ',
        'map.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}