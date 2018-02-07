<?php
namespace yii\easyii2;

use Yii;
use yii\easyii2\models\ModuleDBEasyii2Interface;
use yii\easyii2\models\ModuleEasyii2Interface;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\base\Application;
use yii\base\BootstrapInterface;

use yii\easyii2\models\Module;
use yii\easyii2\models\Setting;
use yii\easyii2\assets\LiveAsset;

/**
 * Class AdminModule
 * @package yii\easyii2
 *
 * @property-read Module[] $activeModules
 * @property-read ModuleEasyii2Interface[]|ModuleDBEasyii2Interface[] $allModules
 */
class AdminModule extends \yii\base\Module implements BootstrapInterface
{
    const VERSION = 0.9;

    public $settings = [
        'modelSeoText' => '\yii\easyii2\models\SeoText'
    ];
    public $activeModules;
    public $controllerLayout = '@easyii2/views/layouts/main';

    private $_installed;

    private $_allModules = null;

    public function init()
    {
        parent::init();

        if (Yii::$app->cache === null) {
            throw new \yii\web\ServerErrorHttpException('Please configure Cache component.');
        }

        $this->activeModules = Module::findAllActive();

        $modules = [];
        foreach ($this->activeModules as $name => $module) {
            $modules[$module->name] = [
                'class' => $module->class,
                'model' => $module,
            ];
            if(empty($module->settings)) $modules[$module->name]['settings'] = $module->settings;

        }
        $this->setModules($modules);

        if (Yii::$app instanceof yii\web\Application) {
            define('IS_ROOT', !Yii::$app->user->isGuest && Yii::$app->user->identity->isRoot());
            define('LIVE_EDIT', !Yii::$app->user->isGuest && Yii::$app->session->get('easyii2_live_edit'));
        }
    }

    public function getAllModules()
    {
        if ($this->_allModules != null) return $this->_allModules;

        $modules = [];
        foreach ($this->modules as $id => $arr) {
            $modules[] = $this->getModule($id);
        }

        return $this->_allModules = $modules;
    }

    public function bootstrap($app)
    {
        Yii::setAlias('easyii2', '@vendor/grozzzny/easyii2');

        if (!$app->user->isGuest && strpos($app->request->pathInfo, 'admin') === false) {
            $app->on(Application::EVENT_BEFORE_REQUEST, function () use ($app) {
                $app->getView()->on(View::EVENT_BEGIN_BODY, [$this, 'renderToolbar']);
            });
        }
    }

    public function renderToolbar()
    {
        $view = Yii::$app->getView();
        echo $view->render('@easyii2/views/layouts/frontend-toolbar.php');
    }

    public function getInstalled()
    {
        if ($this->_installed === null) {
            try {
                $this->_installed = Yii::$app->db->createCommand("SHOW TABLES LIKE 'easyii2_%'")->query()->count() > 0 ? true : false;
            } catch (\Exception $e) {
                $this->_installed = false;
            }
        }
        return $this->_installed;
    }
}
