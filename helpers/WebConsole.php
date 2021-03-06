<?php
namespace yii\easyii2\helpers;

use Yii;
use yii\helpers\FileHelper;

class WebConsole
{
    private static $_console;
    public static $logFile;
    public static $logFileHandler;

    public static function console()
    {
        if(!self::$_console)
        {
            $logsPath = Yii::getAlias('@runtime/logs');
            if(!FileHelper::createDirectory($logsPath, 0777)){
                throw new \yii\web\ServerErrorHttpException('Cannot create `'.$logsPath.'`. Please check write permissions.');
            }

            self::$logFile = $logsPath . DIRECTORY_SEPARATOR . 'console.log';
            self::$logFileHandler = fopen(self::$logFile, 'w+');

            defined('STDIN') or define( 'STDIN',  self::$logFileHandler);
            defined('STDOUT') or define( 'STDOUT',  self::$logFileHandler);

            $consoleConfigFile = Yii::getAlias(Yii::$app->getModule('admin')->consoleConfig);

            $oldApp = Yii::$app;

            if(!file_exists($consoleConfigFile) || !is_array(($consoleConfig = require($consoleConfigFile)))){
                throw new \yii\web\ServerErrorHttpException('Cannot find `'.$consoleConfigFile.'`. Please create and configure console config.');
            }

            self::$_console = new \yii\console\Application($consoleConfig);

            Yii::$app = $oldApp;
        } else {
            ftruncate(self::$logFileHandler, 0);
        }

        return self::$_console;
    }

    public static function migrate($migrationPath = '@easyii2/migrations/')
    {
        ob_start();

        self::console()->runAction('migrate', ['migrationPath' => $migrationPath, 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }
}