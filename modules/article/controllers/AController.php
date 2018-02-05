<?php
namespace yii\easyii2\modules\article\controllers;

use yii\easyii2\components\CategoryController;

class AController extends CategoryController
{
    /** @var string  */
    public $categoryClass = 'yii\easyii2\modules\article\models\Category';

    /** @var string  */
    public $moduleName = 'article';
}