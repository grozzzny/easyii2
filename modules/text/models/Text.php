<?php
namespace yii\easyii2\modules\text\models;

use Yii;
use yii\easyii2\behaviors\CacheFlush;


/**
 * Class Text
 * @package yii\easyii2\modules\text\models
 *
 * @property int $text_id [int(11)]
 * @property string $text
 * @property string $slug [varchar(128)]
 */
class Text extends \yii\easyii2\components\ActiveRecord
{
    const CACHE_KEY = 'easyii2_text';

    public static function tableName()
    {
        return 'easyii2_texts';
    }

    public function rules()
    {
        return [
            ['text_id', 'number', 'integerOnly' => true],
            ['text', 'required'],
            ['text', 'trim'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii2', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['slug', 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'text' => Yii::t('easyii2', 'Text'),
            'slug' => Yii::t('easyii2', 'Slug'),
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className()
        ];
    }
}