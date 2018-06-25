<?php
namespace yii\easyii2\modules\article\models;

use yii\easyii2\components\ActiveRecord;

/**
 * Class Category
 * @package yii\easyii2\modules\article\models
 *
 * @property int $category_id [int(11)]
 * @property string $title [varchar(128)]
 * @property string $image [varchar(128)]
 * @property int $order_num [int(11)]
 * @property string $slug [varchar(128)]
 * @property string $tree [int(11)]
 * @property int $lft [int(11)]
 * @property int $rgt [int(11)]
 * @property int $depth [int(11)]
 * @property bool $status [tinyint(1)]
 */
class Category extends \yii\easyii2\components\CategoryModel
{
    public static function tableName()
    {
        return 'easyii2_article_categories';
    }

    public function getItems()
    {
        $model = ActiveRecord::getModelByName('Item', 'article');
        return $this->hasMany($model::className(), ['category_id' => 'category_id'])->sortDate();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach ($this->getItems()->all() as $item) {
            $item->delete();
        }
    }
}