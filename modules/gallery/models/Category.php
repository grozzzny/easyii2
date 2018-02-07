<?php
namespace yii\easyii2\modules\gallery\models;

use yii\easyii2\models\Photo;

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