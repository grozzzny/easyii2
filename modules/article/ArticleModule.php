<?php
namespace yii\easyii2\modules\article;

class ArticleModule extends \yii\easyii2\components\Module
{
    public $settings = [
        'categoryThumb' => true,
        'articleThumb' => true,
        'enablePhotos' => true,

        'enableShort' => true,
        'shortMaxLength' => 255,
        'enableTags' => true,

        'itemsInFolder' => false,

        'modelCategory' => '\yii\easyii2\modules\article\models\Category',
        'modelItem' => '\yii\easyii2\modules\article\models\Item',
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Articles',
            'ru' => 'Статьи',
        ],
        'icon' => 'pencil',
        'order_num' => 65,
    ];
}