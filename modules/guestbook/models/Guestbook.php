<?php
namespace yii\easyii2\modules\guestbook\models;

use Yii;
use yii\easyii2\behaviors\CalculateNotice;
use yii\easyii2\helpers\Mail;
use yii\easyii2\models\Setting;
use yii\easyii2\validators\ReCaptchaValidator;
use yii\easyii2\validators\EscapeValidator;
use yii\helpers\Url;

/**
 * Class Guestbook
 * @package yii\easyii2\modules\guestbook\models
 *
 * @property int $guestbook_id [int(11)]
 * @property string $name [varchar(128)]
 * @property string $title [varchar(128)]
 * @property string $text
 * @property string $answer
 * @property string $email [varchar(128)]
 * @property int $time [int(11)]
 * @property string $ip [varchar(16)]
 * @property bool $new [tinyint(1)]
 * @property bool $status [tinyint(1)]
 */
class Guestbook extends \yii\easyii2\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    const FLASH_KEY = 'eaysiicms_guestbook_send_result';

    public $reCaptcha;

    public static function tableName()
    {
        return 'easyii2_guestbook';
    }

    public function rules()
    {
        return [
            [['name', 'text'], 'required'],
            [['name', 'title', 'text'], 'trim'],
            [['name', 'title', 'text'], EscapeValidator::className()],
            ['email', 'email'],
            ['title', 'string', 'max' => 128],
            ['reCaptcha', ReCaptchaValidator::className(), 'on' => 'send', 'when' => function(){
                return Yii::$app->getModule('admin')->getModule('guestbook')->settings['enableCaptcha'];
            }],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->ip = Yii::$app->request->userIP;
                $this->time = time();
                $this->new = 1;
                $this->status = Yii::$app->getModule('admin')->getModule('guestbook')->settings['preModerate'] ? self::STATUS_OFF : self::STATUS_ON;
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if($insert){
            $this->mailAdmin();
        }
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('easyii2', 'Name'),
            'title' => Yii::t('easyii2', 'Title'),
            'email' => 'E-mail',
            'text' => Yii::t('easyii2', 'Text'),
            'answer' => Yii::t('easyii2/guestbook', 'Answer'),
            'reCaptcha' => Yii::t('easyii2', 'Anti-spam check')
        ];
    }

    public function behaviors()
    {
        return [
            'cn' => [
                'class' => CalculateNotice::className(),
                'callback' => function(){
                    return self::find()->where(['new' => 1])->count();
                }
            ]
        ];
    }

    public function mailAdmin()
    {
        $settings = Yii::$app->getModule('admin')->getModule('guestbook')->settings;

        if(!$settings['mailAdminOnNewPost']){
            return false;
        }
        return Mail::send(
            Setting::get('admin_email'),
            $settings['subjectOnNewPost'],
            $settings['templateOnNewPost'],
            [
                'post' => $this,
                'link' => Url::to(['/admin/guestbook/a/view', 'id' => $this->primaryKey], true)
            ]
        );
    }

    public function notifyUser()
    {
        $settings = Yii::$app->getModule('admin')->getModule('guestbook')->settings;

        return Mail::send(
            $this->email,
            $settings['subjectNotifyUser'],
            $settings['templateNotifyUser'],
            [
                'post' => $this,
                'link' => Url::to([$settings['frontendGuestbookRoute']], true)
            ]
        );
    }
}