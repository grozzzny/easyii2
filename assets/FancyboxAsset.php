<?php
namespace yii\easyii2\assets;

class FancyboxAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@grozzzny/depends/fancybox/dist';

    public $css = [
        'jquery.fancybox.min.css',
    ];
    public $js = [
        'jquery.fancybox.min.js'
    ];

    public $depends = ['yii\web\JqueryAsset'];
}