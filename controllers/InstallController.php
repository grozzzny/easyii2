<?php
namespace yii\easyii2\controllers;

use Yii;
use yii\easyii2\helpers\Data;
use yii\web\ServerErrorHttpException;

use yii\easyii2\helpers\WebConsole;
use yii\easyii2\models\InstallForm;
use yii\easyii2\models\LoginForm;
use yii\easyii2\models\Module;
use yii\easyii2\models\Setting;

class InstallController extends \yii\web\Controller
{
    public $layout = 'empty';

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->registerI18n();
            return true;
        } else {
            return false;
        }
    }

    public function actionIndex()
    {
        if(!$this->checkDbConnection()){
            $configFile = str_replace(Yii::getAlias('@webroot'), '', Yii::getAlias('@app')).'/config/db.php';
            return $this->showError(Yii::t('easyii2/install', 'Cannot connect to database. Please configure `{0}`.', $configFile));
        }
        if($this->module->installed){
            return $this->showError(Yii::t('easyii2/install', 'easyii2CMS is already installed. If you want to reinstall easyii2CMS, please drop all tables with prefix `easyii2_` from your database manually.'));
        }

        $installForm = new InstallForm();

        if ($installForm->load(Yii::$app->request->post())) {
            $this->createUploadsDir();

            WebConsole::migrate();

            $this->insertSettings($installForm);
            $this->installModules();

            Yii::$app->cache->flush();
            Yii::$app->session->setFlash(InstallForm::ROOT_PASSWORD_KEY, $installForm->root_password);

            return $this->redirect(['/admin/install/finish']);
        }
        else {
            $installForm->robot_email = 'noreply@'.Yii::$app->request->serverName;

            return $this->render('index', [
                'model' => $installForm
            ]);
        }
    }

    public function actionFinish()
    {
        $root_password = Yii::$app->session->getFlash(InstallForm::ROOT_PASSWORD_KEY, true);
        $returnRoute = Yii::$app->session->getFlash(InstallForm::RETURN_URL_KEY, '/admin');

        if($root_password)
        {
            $loginForm = new LoginForm([
                'username' => 'root',
                'password' => $root_password,
            ]);
            if($loginForm->login()){
                return $this->redirect([$returnRoute]);
            }
        }

        return $this->render('finish');
    }

    private function registerI18n()
    {
        Yii::$app->i18n->translations['easyii2/install'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@easyii2/messages',
            'fileMap' => [
                'easyii2/install' => 'install.php',
            ]
        ];
    }

    private function checkDbConnection()
    {
        try{
            Yii::$app->db->open();
            return true;
        }
        catch(\Exception $e){
            return false;
        }
    }

    private function showError($text)
    {
        return $this->render('error', ['error' => $text]);
    }

    private function createUploadsDir()
    {
        $uploadsDir = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'uploads';
        $uploadsDirExists = file_exists($uploadsDir);
        if(($uploadsDirExists && !is_writable($uploadsDir)) || (!$uploadsDirExists && !mkdir($uploadsDir, 0777))){
            throw new ServerErrorHttpException('Cannot create uploads folder at `'.$uploadsDir.'` Please check write permissions.');
        }
    }

    private function insertSettings($installForm)
    {
        $db = Yii::$app->db;
        $password_salt = Yii::$app->security->generateRandomString();
        $root_auth_key = Yii::$app->security->generateRandomString();
        $root_password = sha1($installForm->root_password.$root_auth_key.$password_salt);

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'recaptcha_key',
            'value' => $installForm->recaptcha_key,
            'title' => Yii::t('easyii2/install', 'ReCaptcha key'),
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'password_salt',
            'value' => $password_salt,
            'title' => 'Password salt',
            'visibility' => Setting::VISIBLE_NONE
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'root_auth_key',
            'value' => $root_auth_key,
            'title' => 'Root authorization key',
            'visibility' => Setting::VISIBLE_NONE
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'root_password',
            'value' => $root_password,
            'title' => Yii::t('easyii2/install', 'Root password'),
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'auth_time',
            'value' => 86400,
            'title' => Yii::t('easyii2/install', 'Auth time'),
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'robot_email',
            'value' => $installForm->robot_email,
            'title' => Yii::t('easyii2/install', 'Robot E-mail'),
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'admin_email',
            'value' => $installForm->admin_email,
            'title' => Yii::t('easyii2/install', 'Admin E-mail'),
            'visibility' => Setting::VISIBLE_ALL
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'recaptcha_secret',
            'value' => $installForm->recaptcha_secret,
            'title' => Yii::t('easyii2/install', 'ReCaptcha secret'),
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'toolbar_position',
            'value' => 'top',
            'title' => Yii::t('easyii2/install', 'Frontend toolbar position').' ("top" or "bottom")',
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();
    }

    private function installModules()
    {
        $language = Data::getLocale();

        foreach(glob(Yii::getAlias('@easyii2'). DIRECTORY_SEPARATOR .'modules/*') as $module)
        {
            $moduleName = basename($module);
            $moduleClass = 'yii\easyii2\modules\\' . $moduleName . '\\' . ucfirst($moduleName) . 'Module';
            $moduleConfig = $moduleClass::$installConfig;

            $module = new Module([
                'name' => $moduleName,
                'class' => $moduleClass,
                'title' => !empty($moduleConfig['title'][$language]) ? $moduleConfig['title'][$language] : $moduleConfig['title']['en'],
                'icon' => $moduleConfig['icon'],
                'settings' => Yii::createObject($moduleClass, [$moduleName])->settings,
                'order_num' => $moduleConfig['order_num'],
                'status' => Module::STATUS_ON,
            ]);
            $module->save();
        }
    }
}
