<?php
namespace yii\easyii2\modules\page\models;

use Yii;
use yii\easyii2\behaviors\SeoBehavior;

class Page extends \yii\easyii2\components\ActiveRecord
{
    public static function tableName()
    {
        return 'easyii2_pages';
    }

    public function rules()
    {
        return [
            ['title', 'required'],
            [['title', 'text'], 'trim'],
            ['title', 'string', 'max' => 128],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii2', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['slug', 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii2', 'Title'),
            'text' => Yii::t('easyii2', 'Text'),
            'slug' => Yii::t('easyii2', 'Slug'),
        ];
    }

    public function behaviors()
    {
        return [
            'seoBehavior' => SeoBehavior::className(),
        ];
    }
}