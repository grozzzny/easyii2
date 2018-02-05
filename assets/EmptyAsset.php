<?php
namespace yii\easyii2\assets;

class EmptyAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii2/media';
    public $css = [
        'css/empty.css',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
