<?php
namespace yii\easyii2\models;

use Yii;

use yii\easyii2\helpers\Data;
use yii\easyii2\behaviors\CacheFlush;

/**
 * Class Setting
 * @package yii\easyii2\models
 *
 * @property int $setting_id [int(11)]
 * @property string $name [varchar(64)]
 * @property string $title [varchar(128)]
 * @property string $value [varchar(1024)]
 * @property bool $visibility [tinyint(1)]
 */
class Setting extends \yii\easyii2\components\ActiveRecord
{
    const VISIBLE_NONE = 0;
    const VISIBLE_ROOT = 1;
    const VISIBLE_ALL = 2;

    const CACHE_KEY = 'easyii2_settings';

    static $_data;

    public static function tableName()
    {
        return 'easyii2_settings';
    }

    public function rules()
    {
        return [
            [['name', 'title', 'value'], 'required'],
            [['name', 'title', 'value'], 'trim'],
            ['name',  'match', 'pattern' => '/^[a-zA-Z][\w_-]*$/'],
            ['name', 'unique'],
            ['visibility', 'number', 'integerOnly' => true]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('easyii2', 'Name'),
            'title' => Yii::t('easyii2', 'Title'),
            'value' => Yii::t('easyii2', 'Value'),
            'visibility' => Yii::t('easyii2', 'Only for developer')
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className()
        ];
    }

    public static function get($name)
    {
        if(!self::$_data){
            self::$_data =  Data::cache(self::CACHE_KEY, 3600, function(){
                $result = [];
                try {
                    foreach (parent::find()->all() as $setting) {
                        $result[$setting->name] = $setting->value;
                    }
                }catch(\yii\db\Exception $e){}
                return $result;
            });
        }
        return isset(self::$_data[$name]) ? self::$_data[$name] : null;
    }

    public static function set($name, $value)
    {
        if(self::get($name)){
            $setting = Setting::find()->where(['name' => $name])->one();
            $setting->value = $value;
        } else {
            $setting = new Setting([
                'name' => $name,
                'value' => $value,
                'title' => $name,
                'visibility' => self::VISIBLE_NONE
            ]);
        }
        $setting->save();
    }
}