<?php
namespace yii\easyii2\assets;

class FrontendAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii2/media';
    public $css = [
        'css/frontend.css',
    ];
    public $js = [
        'js/frontend.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\easyii2\assets\SwitcherAsset'
    ];
}
