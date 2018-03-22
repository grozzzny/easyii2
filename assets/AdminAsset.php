<?php
namespace yii\easyii2\assets;

class AdminAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii2/media';
    public $css = [
        'css/admin.css',
    ];
    public $js = [
        'js/admin.js',
        'js/translit.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\easyii2\assets\SwitcherAsset',
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
}
