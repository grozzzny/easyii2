<?php
namespace yii\easyii2\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\Json;

use yii\easyii2\assets\FancyboxAsset;

class Fancybox extends Widget
{
    public $options = [];
    public $selector;

    public function init()
    {
        parent::init();

        if (empty($this->selector)) {
            throw new InvalidConfigException('Required `selector` param isn\'t set.');
        }
    }

    public function run()
    {
        $clientOptions = (count($this->options)) ? Json::encode($this->options) : '';

        $this->view->registerAssetBundle(FancyboxAsset::className());
        $this->view->registerJs('$("'.$this->selector.'").fancybox('.$clientOptions.');');
    }
}