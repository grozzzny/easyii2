<?php
namespace yii\easyii2\assets;

class AdminlteEasyiicmsAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii2/media';

    public $css = [
        'adminlte_easyiicms.css',
    ];
    public $depends = [
        'grozzzny\depends\jquery_migrate\JqueryMigrateAsset',
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
}
