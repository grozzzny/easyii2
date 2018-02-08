<?php
namespace yii\easyii2\models;

use Yii;
use yii\easyii2\behaviors\SortableModel;

/**
 * Class Photo
 * @package yii\easyii2\models
 *
 * @property integer $photo_id
 * @property string $class
 * @property integer $item_id
 * @property string $image
 * @property string $description
 * @property integer $order_num
 */
class Photo extends \yii\easyii2\components\ActiveRecord
{
    const PHOTO_MAX_WIDTH = 1900;
    const PHOTO_THUMB_WIDTH = 120;
    const PHOTO_THUMB_HEIGHT = 90;

    public static function tableName()
    {
        return 'easyii2_photos';
    }

    public function rules()
    {
        return [
            [['class', 'item_id'], 'required'],
            ['item_id', 'integer'],
            ['image', 'image'],
            ['description', 'trim']
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className()
        ];
    }

    public function afterDelete()
    {
        parent::afterDelete();

        @unlink(Yii::getAlias('@webroot').$this->image);
    }
}