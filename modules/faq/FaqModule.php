<?php
namespace yii\easyii2\modules\faq;

use Yii;

class FaqModule extends \yii\easyii2\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'FAQ',
            'ru' => 'Вопросы и ответы',
        ],
        'icon' => 'question-sign',
        'order_num' => 45,
    ];
}