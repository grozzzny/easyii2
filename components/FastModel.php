<?php
namespace yii\easyii2\components;

use yii\easyii2\behaviors\CacheFlush;
use Yii;
use yii\easyii2\behaviors\SortableModel;

class FastModel extends \yii\easyii2\components\ActiveRecord
{
    use TraitModel;

    const STATUS_OFF = 0;
    const STATUS_ON = 1;

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
}