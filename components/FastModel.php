<?php
namespace yii\easyii2\components;

use yii\easyii2\behaviors\CacheFlush;
use Yii;
use yii\easyii2\behaviors\SortableModel;
use yii\easyii2\models\Photo;
use yii\easyii2\modules\gallery\api\PhotoObject;

/**
 * Class FastModel
 * @package yii\easyii2\components
 *
 * @property-read PhotoObject[] $photos
 */
class FastModel extends \yii\easyii2\components\ActiveRecord
{
    use TraitModel;

    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    private $_photos = [];

    public function behaviors()
    {
        return [
            CacheFlush::className(),
            SortableModel::className()
        ];
    }

    /**
     * Используется при отчистке ранее загруженных файлов
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$insert){
                foreach ($this->getAttributes() as $attribute => $value){
                    if($this->hasValidator(['image', 'file'], $attribute)) {
                        if($this->$attribute !== $this->oldAttributes[$attribute]){
                            @unlink(Yii::getAlias('@webroot') . $this->oldAttributes[$attribute]);
                        }
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Используется при отчистке файлов
     */
    public function afterDelete()
    {
        parent::afterDelete();

        foreach ($this->getAttributes() as $attribute => $value){
            if($this->hasValidator(['image', 'file'], $attribute)) {
                @unlink(Yii::getAlias('@webroot').$this->$attribute);
            }
        }
    }

    public function enablePhotoManager()
    {
        return false;
    }

    public function getPhotos()
    {
        if(empty($this->_photos) && $this->enablePhotoManager()){
            $photos = Photo::find()
                ->where([
                    'class' => self::className(),
                    'item_id' => $this->id
                ])
                ->sort()
                ->all();

            foreach($photos as $model){
                $this->_photos[] = new PhotoObject($model);
            }
        }
        return $this->_photos;
    }
}