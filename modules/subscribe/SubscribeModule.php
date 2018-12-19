<?php
namespace yii\easyii2\modules\subscribe;

use Yii;

class SubscribeModule extends \yii\easyii2\components\Module
{

    public $title = 'Subscribe';

    public $settings = [
        'modelSubscriber' => '\yii\easyii2\modules\subscribe\models\Subscriber',
        'modelHistory' => '\yii\easyii2\modules\subscribe\models\History',
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'E-mail subscribe',
            'ru' => 'E-mail рассылка',
        ],
        'icon' => 'envelope',
        'order_num' => 10,
    ];

    public function getTitle()
    {
        return \Yii::t('easyii2/subscribe', $this->title);
    }

    public function init()
    {
        self::registerTranslation();
        parent::init();
    }

    /**
     * Registers translations connected to the module
     */
    public static function registerTranslation()
    {

        Yii::$app->i18n->translations['easyii2/subscribe*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@easyii2/modules/subscribe/messages',
            'fileMap' => [
                'easyii2/subscribe' => 'admin.php',
                'easyii2/subscribe/api' => 'api.php'
            ]
        ];
    }
}