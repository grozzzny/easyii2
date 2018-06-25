<?php

namespace yii\easyii2\models;

use yii\easyii2\modules\gallery\models\Category;

/**
 * Class TreeObject
 * @package yii\easyii2\models
 *
 * @property-read integer $category_id
 * @property-read string $title
 * @property-read string $image
 * @property-read string $slug
 * @property-read string $tree
 * @property-read string $depth
 * @property-read string $status
 * @property-read string $text
 * @property-read SeoText $seo
 * @property-read array $children
 * @property-read Category | \yii\easyii2\modules\article\models\Category $model
 */
class TreeObject
{

}