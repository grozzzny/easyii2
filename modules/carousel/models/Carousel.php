<?php
namespace yii\easyii2\modules\carousel\models;

use Yii;
use yii\easyii2\behaviors\CacheFlush;
use yii\easyii2\behaviors\SortableModel;

class Carousel extends \yii\easyii2\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    const CACHE_KEY = 'easyii2_carousel';

    public static function tableName()
    {
        return 'easyii2_carousel';
    }

    public function rules()
    {
        return [
            ['image', 'image'],
            [['title', 'text', 'link'], 'trim'],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON],
        ];
    }

    public function attributeLabels()
    {
        return [
            'image' => Yii::t('easyii2', 'Image'),
            'link' =>  Yii::t('easyii2', 'Link'),
            'title' => Yii::t('easyii2', 'Title'),
            'text' => Yii::t('easyii2', 'Text'),
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className(),
            SortableModel::className()
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$insert && $this->image != $this->oldAttributes['image'] && $this->oldAttributes['image']){
                @unlink(Yii::getAlias('@webroot').$this->oldAttributes['image']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        @unlink(Yii::getAlias('@webroot').$this->image);
    }
}