<?php
namespace yii\easyii2\modules\faq\models;

use Yii;
use yii\easyii2\behaviors\CacheFlush;
use yii\easyii2\behaviors\SortableModel;

class Faq extends \yii\easyii2\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    const CACHE_KEY = 'easyii2_faq';

    public static function tableName()
    {
        return 'easyii2_faq';
    }

    public function rules()
    {
        return [
            [['question','answer'], 'required'],
            [['question', 'answer'], 'trim'],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON],
        ];
    }

    public function attributeLabels()
    {
        return [
            'question' => Yii::t('easyii2/faq', 'Question'),
            'answer' => Yii::t('easyii2/faq', 'Answer'),
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className(),
            SortableModel::className()
        ];
    }
}