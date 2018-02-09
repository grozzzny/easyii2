<?php
namespace yii\easyii2\models;

use Yii;

use yii\easyii2\helpers\Data;
use yii\easyii2\behaviors\CacheFlush;
use yii\easyii2\behaviors\SortableModel;

/**
 * Class Module
 * @package yii\easyii2\models
 *
 * @property int $module_id [int(11)]
 * @property string $name [varchar(64)]
 * @property string $class [varchar(128)]
 * @property string $title [varchar(128)]
 * @property string $icon [varchar(32)]
 * @property string $settings
 * @property int $notice [int(11)]
 * @property int $order_num [int(11)]
 * @property bool $status [tinyint(1)]
 *
 * @property-read array $defaultSettings
 */
class Module extends \yii\easyii2\components\ActiveRecord
{
    const STATUS_OFF= 0;
    const STATUS_ON = 1;

    const CACHE_KEY = 'easyii2_modules';

    public static function tableName()
    {
        return 'easyii2_modules';
    }

    public function rules()
    {
        return [
            [['name', 'class', 'title'], 'required'],
            [['name', 'class', 'title', 'icon'], 'trim'],
            ['name',  'match', 'pattern' => '/^[a-z]+$/'],
            ['name', 'unique'],
            ['class',  'match', 'pattern' => '/^[\w\\\]+$/'],
            ['class',  'checkExists'],
            ['icon', 'string'],
            ['status', 'default', 'value' => 1],
            ['status', 'in', 'range' => [0,1]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('easyii2', 'Name'),
            'class' => Yii::t('easyii2', 'Class'),
            'title' => Yii::t('easyii2', 'Title'),
            'icon' => Yii::t('easyii2', 'Icon'),
            'order_num' => Yii::t('easyii2', 'Order'),
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
            if(!$this->settings || !is_array($this->settings)){
                $this->settings = $this->defaultSettings;
            }
            $this->settings = json_encode($this->settings);

            return true;
        } else {
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->settings = $this->settings !== '' ? json_decode($this->settings, true) : $this->defaultSettings;
    }

    /**
     * @return Module[]
     */
    public static function findAllActive()
    {
        return Data::cache(self::CACHE_KEY, 3600, function(){
            return self::find()->where(['status' => self::STATUS_ON])->sort()->all();
        });
    }

    public function setSettings($settings)
    {
        $newSettings = [];
        foreach($this->settings as $key => $value){
            $newSettings[$key] = is_bool($value) ? ($settings[$key] ? true : false) : ($settings[$key] ? $settings[$key] : '');
        }
        $this->settings = $newSettings;
    }

    public function checkExists($attribute)
    {
        if(!class_exists($this->$attribute)){
            $this->addError($attribute, Yii::t('easyii2', 'Class does not exist'));
        }
    }

    public function getDefaultSettings()
    {
        $exemplar = Yii::createObject($this->class, [$this->name]);
        return isset($exemplar->settings) ? $exemplar->settings : [];
    }

}