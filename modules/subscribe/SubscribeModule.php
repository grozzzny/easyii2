<?php
namespace yii\easyii2\modules\subscribe;

class SubscribeModule extends \yii\easyii2\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'E-mail subscribe',
            'ru' => 'E-mail рассылка',
        ],
        'icon' => 'envelope',
        'order_num' => 10,
    ];
}