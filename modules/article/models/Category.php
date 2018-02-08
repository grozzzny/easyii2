<?php
namespace yii\easyii2\modules\article\models;

use yii\easyii2\components\ActiveRecord;

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