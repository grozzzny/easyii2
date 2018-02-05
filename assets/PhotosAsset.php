<?php
namespace yii\easyii2\assets;

class PhotosAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii2/assets/photos';
    public $css = [
        'photos.css',
    ];
    public $js = [
        'photos.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
