<?php
namespace yii\easyii2\modules\feedback\models;

use Yii;
use yii\easyii2\behaviors\CalculateNotice;
use yii\easyii2\helpers\Mail;
use yii\easyii2\models\Setting;
use yii\easyii2\validators\ReCaptchaValidator;
use yii\easyii2\validators\EscapeValidator;
use yii\helpers\Url;


/**
 * Class Feedback
 * @package yii\easyii2\modules\feedback\models
 * 
 * @property integer $feedback_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $title
 * @property string $text
 * @property string $answer_subject
 * @property string $answer_text
 * @property integer $time
 * @property string $ip
 * @property integer $status
 * 
 */
class Feedback extends \yii\easyii2\components\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_VIEW = 1;
    const STATUS_ANSWERED = 2;

    const FLASH_KEY = 'eaysiicms_feedback_send_result';

    public $reCaptcha;

    public static function tableName()
    {
        return 'easyii2_feedback';
    }

    public function rules()
    {
        return [
            [['name', 'email', 'text'], 'required'],
            [['name', 'email', 'phone', 'title', 'text'], 'trim'],
            [['name','title', 'text'], EscapeValidator::className()],
            ['title', 'string', 'max' => 128],
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^[\d\s-\+\(\)]+$/'],
            ['reCaptcha', ReCaptchaValidator::className(), 'when' => function($model){
                return $model->isNewRecord && Yii::$app->getModule('admin')->getModule('feedback')->settings['enableCaptcha'];
            }],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->ip = Yii::$app->request->userIP;
                $this->time = time();
                $this->status = self::STATUS_NEW;
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
            'email' => 'E-mail',
            'name' => Yii::t('easyii2', 'Name'),
            'title' => Yii::t('easyii2', 'Title'),
            'text' => Yii::t('easyii2', 'Text'),
            'answer_subject' => Yii::t('easyii2/feedback', 'Subject'),
            'answer_text' => Yii::t('easyii2', 'Text'),
            'phone' => Yii::t('easyii2/feedback', 'Phone'),
            'reCaptcha' => Yii::t('easyii2', 'Anti-spam check')
        ];
    }

    public function behaviors()
    {
        return [
            'cn' => [
                'class' => CalculateNotice::className(),
                'callback' => function(){
                    return self::find()->status(self::STATUS_NEW)->count();
                }
            ]
        ];
    }

    public function mailAdmin()
    {
        $settings = Yii::$app->getModule('admin')->getModule('feedback')->settings;
        if(!$settings['mailAdminOnNewFeedback']){
            return false;
        }
        return Mail::send(
            Setting::get('admin_email'),
            $settings['subjectOnNewFeedback'],
            $settings['templateOnNewFeedback'],
            ['feedback' => $this, 'link' => Url::to(['/admin/feedback/a/view', 'id' => $this->primaryKey], true)]
        );
    }

    public function sendAnswer()
    {
        $settings = Yii::$app->getModule('admin')->getModule('feedback')->settings;

        return Mail::send(
            $this->email,
            $this->answer_subject,
            $settings['answerTemplate'],
            ['feedback' => $this],
            ['replyTo' => Setting::get('admin_email')]
        );
    }
}