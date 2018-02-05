<?php
namespace yii\easyii2\modules\catalog\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii2\behaviors\SeoBehavior;
use yii\easyii2\behaviors\SortableModel;
use yii\easyii2\models\Photo;

class ItemData extends \yii\easyii2\components\ActiveRecord
{

    public static function tableName()
    {
        return 'easyii2_catalog_item_data';
    }
}