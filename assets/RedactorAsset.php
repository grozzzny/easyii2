<?php
namespace yii\easyii2\assets;

class RedactorAsset extends \yii\web\AssetBundle
{

    public $sourcePath = '@easyii2/assets/redactor';
    public $depends = ['yii\web\JqueryAsset'];

    public function init()
    {
        $this->js[] = 'redactor2.js';
        $this->css[] = 'redactor2.css';
    }

}