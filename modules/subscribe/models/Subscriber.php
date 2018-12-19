<?php
namespace yii\easyii2\modules\subscribe\models;

use Yii;

/**
 * Class Subscriber
 * @package yii\easyii2\modules\subscribe\models
 * @property int $subscriber_id [int(11)]
 * @property string $email [varchar(128)]
 * @property string $ip [varchar(16)]
 * @property int $time [int(11)]
 */
class Subscriber extends \yii\easyii2\components\ActiveRecord
{
    const FLASH_KEY = 'eaysiicms_subscribe_send_result';

    public static function tableName()
    {
        return 'easyii2_subscribe_subscribers';
    }

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'unique'],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->ip = Yii::$app->request->userIP;
                $this->time = time();
            }
            return true;
        } else {
            return false;
        }
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
        ];
    }
}