<?php
namespace yii\easyii2\modules\subscribe\models;

use Yii;

class History extends \yii\easyii2\components\ActiveRecord
{
    public static function tableName()
    {
        return 'easyii2_subscribe_history';
    }

    public function rules()
    {
        return [
            [['subject', 'body'], 'required'],
            ['subject', 'trim'],
            ['sent', 'number', 'integerOnly' => true],
            ['time', 'default', 'value' => time()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'subject' => Yii::t('easyii2/subscribe', 'Subject'),
            'body' => Yii::t('easyii2/subscribe', 'Body'),
        ];
    }
}