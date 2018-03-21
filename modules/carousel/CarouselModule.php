<?php
namespace yii\easyii2\modules\carousel;

class CarouselModule extends \yii\easyii2\components\Module
{
    public $settings = [
        'enableTitle' => true,
        'enableText' => true,
        'modelCarousel' => '\yii\easyii2\modules\carousel\models\Carousel',
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Carousel',
            'ru' => 'Карусель',
        ],
        'icon' => 'picture',
        'order_num' => 40,
    ];
}