<?php
namespace yii\easyii2\components;

use Yii;
use yii\easyii2\models\Module as ModuleModel;
use yii\easyii2\models\ModuleDBEasyii2Interface;


/**
 * Base module class. Inherit from this if you are creating your own modules manually
 * @package yii\easyii2\components
 */
class Module extends \yii\base\Module implements ModuleDBEasyii2Interface
{
    /**
     * @var \yii\easyii2\models\Module
     */
    public $model;

    /** @var string  */
    public $defaultRoute = 'a';

    /** @var array  */
    public $settings = [];

    /** @var  @todo */
    public $i18n;

    public $notice;

    /**
     * Configuration for installation
     * @var array
     */
    public static $installConfig = [
        'title' => [
            'en' => 'Custom Module',
        ],
        'icon' => 'asterisk',
        'order_num' => 0,
    ];

    public function init()
    {
        parent::init();

        $moduleName = self::getModuleName(self::className());
        self::registerTranslations($moduleName);
    }

    /**
     * Registers translations connected to the module
     * @param $moduleName string
     */
    public static function registerTranslations($moduleName)
    {
        $moduleClassFile = '';
        foreach(ModuleModel::findAllActive() as $name => $module){
            if($name == $moduleName){
                $moduleClassFile = (new \ReflectionClass($module->class))->getFileName();
                break;
            }
        }

        if($moduleClassFile){
            Yii::$app->i18n->translations['easyii2/'.$moduleName.'*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => dirname($moduleClassFile) . DIRECTORY_SEPARATOR . 'messages',
                'fileMap' => [
                    'easyii2/'.$moduleName => 'admin.php',
                    'easyii2/'.$moduleName.'/api' => 'api.php'
                ]
            ];
        }
    }

    /**
     * Module name getter
     *
     * @param $namespace
     * @return string|bool
     */
    public static function getModuleName($namespace)
    {
        foreach(ModuleModel::findAllActive() as $module)
        {
            $moduleClassPath = preg_replace('/[\w]+$/', '', $module->class);
            if(strpos($namespace, $moduleClassPath) !== false){
                return $module->name;
            }
        }
        return false;
    }

    public function getTitle()
    {
        // TODO: Implement getTitle() method.
        return $this->model->title;
    }

    public function getName()
    {
        // TODO: Implement getName() method.
        return $this->model->name;
    }

    public function getIcon()
    {
        // TODO: Implement getIcon() method.
        return $this->model->icon;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getSettings()
    {
        // TODO: Implement getSettings() method.
        return $this->settings;
    }
}