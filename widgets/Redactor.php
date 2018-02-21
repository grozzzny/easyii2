<?php
namespace yii\easyii2\widgets;

use Yii;
use yii\easyii2\helpers\Data;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\AssetBundle;

use yii\easyii2\assets\RedactorAsset;

/**
 * Class Redactor
 * @package yii\easyii2\widgets
 *
 * @property string $idBox
 */
class Redactor extends InputWidget
{
    public $options = [];

    private $_defaultOptions = [
        'imageUpload' => '/admin/redactor/upload',
        'fileUpload' => '/admin/redactor/upload',
       // 'formatting' => ['blockquote', 'h2', 'p'],
        'imagePosition' => true,
        'imageResizable' => true
    ];
    private $_assetBundle;

    public function init()
    {
        $this->options = array_merge($this->_defaultOptions, ['toolbarFixedTarget' => '#'.$this->idBox], $this->options);

        if (isset($this->options['imageUpload'])) {
            $this->options['imageUploadErrorCallback'] = new JsExpression("function(json){alert(json.error);}");
        }
        if (isset($this->options['fileUpload'])) {
            $this->options['fileUploadErrorCallback'] = new JsExpression("function(json){alert(json.error);}");
        }
        $this->registerAssetBundle();
        $this->registerRegional();
        $this->registerPlugins();
        $this->registerScript();
    }

    public function run()
    {
        echo Html::beginTag('div', ['id' => $this->idBox, 'style' => 'position: relative; overflow: auto;']);
        echo Html::activeTextarea($this->model, $this->attribute);
        echo Html::endTag('div');
    }

    public function getIdBox()
    {
        return 'toolbar-box-'.$this->attribute;
    }

    public function registerRegional()
    {
        $lang = Data::getLocale();
        if ($lang != 'en') {
            $langAsset = 'lang/' . $lang . '.js';
            if (file_exists(Yii::getAlias($this->assetBundle->sourcePath . '/' . $langAsset))) {
                $this->assetBundle->js[] = $langAsset;
                $this->options['lang'] = $lang;
            }
        }
    }

    public function registerPlugins()
    {
        if (isset($this->options['plugins']) && count($this->options['plugins'])) {
            foreach ($this->options['plugins'] as $plugin) {
                if($plugin == 'codemirror'){
                    $this->assetBundle->js[] = 'plugins2/codemirror/codemirror.js';
                    $this->assetBundle->js[] = 'plugins2/codemirror/xml.js';
                    $this->assetBundle->css[] = 'plugins2/codemirror/codemirror.css';
                }
                $js = 'plugins2/' . $plugin . '.js';
                if (file_exists(Yii::getAlias($this->assetBundle->sourcePath . DIRECTORY_SEPARATOR . $js))) {
                    $this->assetBundle->js[] = $js;
                }
                $css = 'plugins2/' . $plugin . '.css';
                if (file_exists(Yii::getAlias($this->assetBundle->sourcePath . '/' . $css))) {
                    $this->assetBundle->css[] = $css;
                }
            }
        }
    }

    public function registerScript()
    {
        $clientOptions = (count($this->options)) ? Json::encode($this->options) : '';
        $this->getView()->registerJs("jQuery('#".Html::getInputId($this->model, $this->attribute)."').redactor({$clientOptions});");
    }

    public function registerAssetBundle()
    {
        $this->_assetBundle = RedactorAsset::register($this->getView());
    }

    public function getAssetBundle()
    {
        if (!($this->_assetBundle instanceof AssetBundle)) {
            $this->registerAssetBundle();
        }
        return $this->_assetBundle;
    }

}
