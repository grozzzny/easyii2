<?php
namespace yii\easyii2\modules\shopcart;

use Yii;

class ShopcartModule extends \yii\easyii2\components\Module
{
    public $settings = [
        'mailAdminOnNewOrder' => true,
        'subjectOnNewOrder' => 'New order',
        'templateOnNewOrder' => '@easyii2/modules/shopcart/mail/en/new_order',
        'subjectNotifyUser' => 'Your order status changed',
        'templateNotifyUser' => '@easyii2/modules/shopcart/mail/en/notify_user',
        'frontendShopcartRoute' => '/shopcart/order',
        'enablePhone' => true,
        'enableEmail' => true
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Orders',
            'ru' => 'Заказы',
        ],
        'icon' => 'shopping-cart',
        'order_num' => 120,
    ];


    public static function registerTranslation()
    {
        Yii::$app->i18n->translations['easyii2/shopcart*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@easyii2/modules/shopcart/messages',
            'fileMap' => [
                'easyii2/shopcart' => 'admin.php',
            ]
        ];
    }
}