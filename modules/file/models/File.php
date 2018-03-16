<?php
namespace yii\easyii2\modules\file\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii2\behaviors\SeoBehavior;
use yii\easyii2\behaviors\SortableModel;

/**
 * Class File
 * @package yii\easyii2\modules\file\models
 *
 * @property int $file_id [int(11)]
 * @property string $title [varchar(128)]
 * @property string $file [varchar(255)]
 * @property int $size [int(11)]
 * @property string $slug [varchar(128)]
 * @property int $downloads [int(11)]
 * @property int $time [int(11)]
 * @property int $order_num [int(11)]
 */
class File extends \yii\easyii2\components\ActiveRecord
{
    public static function tableName()
    {
        return 'easyii2_files';
    }

    public function rules()
    {
        return [
            ['file', 'file'],
            [['title', 'slug'], 'required'],
            ['title', 'string', 'max' => 128],
            ['title', 'trim'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii2', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            [['downloads', 'size'], 'integer'],
            ['time', 'default', 'value' => time()]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii2', 'Title'),
            'file' => Yii::t('easyii2', 'File'),
            'slug' => Yii::t('easyii2', 'Slug')
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className(),
            'seoBehavior' => SeoBehavior::className(),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$insert && $this->file !== $this->oldAttributes['file']){
                @unlink(Yii::getAlias('@webroot').$this->oldAttributes['file']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        @unlink(Yii::getAlias('@webroot').$this->file);
    }
}