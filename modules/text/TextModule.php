<?php
namespace yii\easyii2\modules\text;

/**
 * Class TextModule
 * @package yii\easyii2\modules\text
 */
class TextModule extends \yii\easyii2\components\Module
{
    public $settings = [
        'modelText' => '\yii\easyii2\modules\text\models\Text',
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Text blocks',
            'ru' => 'Текстовые блоки',
        ],
        'icon' => 'font',
        'order_num' => 20,
    ];
}