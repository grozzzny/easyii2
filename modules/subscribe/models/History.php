<?php
namespace yii\easyii2\modules\subscribe\models;

use Yii;

/**
 * Class History
 * @package yii\easyii2\modules\subscribe\models
 * @property int $history_id [int(11)]
 * @property string $subject [varchar(128)]
 * @property string $body
 * @property int $sent [int(11)]
 * @property int $time [int(11)]
 */
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