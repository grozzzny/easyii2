<?php
namespace yii\easyii2\modules\page;

use Yii;

class PageModule extends \yii\easyii2\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Pages',
            'ru' => 'Страницы',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}