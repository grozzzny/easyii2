<?php
namespace yii\easyii2\modules\gallery\models;

use yii\easyii2\models\Photo;

/**
 * Class Category
 * @package yii\easyii2\modules\gallery\models
 *
 * @property integer $category_id
 * @property string $title
 * @property string $image
 * @property string $slug
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property integer $order_num
 * @property boolean $status
 */
class Category extends \yii\easyii2\components\CategoryModel
{
    public static function tableName()
    {
        return 'easyii2_gallery_categories';
    }

    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['item_id' => 'category_id'])->where(['class' => self::className()])->sort();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach($this->getPhotos()->all() as $photo){
            $photo->delete();
        }
    }
}