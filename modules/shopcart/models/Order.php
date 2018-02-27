<?php
namespace yii\easyii2\modules\shopcart\models;

use Yii;
use yii\easyii2\behaviors\CalculateNotice;
use yii\easyii2\helpers\Mail;
use yii\easyii2\models\Setting;
use yii\easyii2\validators\EscapeValidator;
use yii\helpers\Url;

/**
 * Class Order
 * @package yii\easyii2\modules\shopcart\models
 *
 * @property int $order_id [int(11)]
 * @property string $name [varchar(64)]
 * @property string $address [varchar(255)]
 * @property string $phone [varchar(64)]
 * @property string $email [varchar(128)]
 * @property string $comment [varchar(1024)]
 * @property string $remark [varchar(1024)]
 * @property string $access_token [varchar(32)]
 * @property string $ip [varchar(16)]
 * @property int $time [int(11)]
 * @property bool $new [tinyint(1)]
 * @property bool $status [tinyint(1)]
 */
class Order extends \yii\easyii2\components\ActiveRecord
{
    const STATUS_BLANK = 0;
    const STATUS_PENDING = 1;
    const STATUS_PROCESSED = 2;
    const STATUS_DECLINED = 3;
    const STATUS_SENT = 4;
    const STATUS_RETURNED = 5;
    const STATUS_ERROR = 6;
    const STATUS_COMPLETED = 7;

    const SESSION_KEY = 'easyii2_shopcart_at';

    public static function tableName()
    {
        return 'easyii2_shopcart_orders';
    }

    public function rules()
    {
        return [
            [['name', 'address'], 'required', 'on' => 'confirm'],
            ['email', 'required', 'when' => function($model){ return $model->scenario == 'confirm' && Yii::$app->getModule('admin')->getModule('shopcart')->settings['enableEmail']; }],
            ['phone', 'required', 'when' => function($model){ return $model->scenario == 'confirm' && Yii::$app->getModule('admin')->getModule('shopcart')->settings['enablePhone']; }],
            [['name', 'address', 'phone', 'comment'], 'trim'],
            ['email', 'email'],
            ['name', 'string', 'max' => 32],
            ['address', 'string', 'max' => 1024],
            ['phone', 'string', 'max' => 32],
            ['phone', 'match', 'pattern' => '/^[\d\s-\+\(\)]+$/'],
            ['comment', 'string', 'max' => 1024],
            [['name', 'address', 'phone', 'comment'], EscapeValidator::className()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('easyii2', 'Name'),
            'email' => Yii::t('easyii2', 'E-mail'),
            'address' => Yii::t('easyii2/shopcart', 'Address'),
            'phone' => Yii::t('easyii2/shopcart', 'Phone'),
            'comment' => Yii::t('easyii2/shopcart', 'Comment'),
            'remark' => Yii::t('easyii2/shopcart', 'Admin remark'),
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

    public static function statusName($status)
    {
        $states = self::states();
        return !empty($states[$status]) ? $states[$status] : $status;
    }

    public static function states()
    {
        return [
            self::STATUS_BLANK => Yii::t('easyii2/shopcart', 'Blank'),
            self::STATUS_PENDING => Yii::t('easyii2/shopcart', 'Pending'),
            self::STATUS_PROCESSED => Yii::t('easyii2/shopcart', 'Processed'),
            self::STATUS_DECLINED => Yii::t('easyii2/shopcart', 'Declined'),
            self::STATUS_SENT => Yii::t('easyii2/shopcart', 'Sent'),
            self::STATUS_RETURNED => Yii::t('easyii2/shopcart', 'Returned'),
            self::STATUS_ERROR => Yii::t('easyii2/shopcart', 'Error'),
            self::STATUS_COMPLETED => Yii::t('easyii2/shopcart', 'Completed'),
        ];
    }

    public function getStatusName()
    {
        $states = self::states();
        return !empty($states[$this->status]) ? $states[$this->status] : $this->status;
    }

    public function getGoods()
    {
        return $this->hasMany(Good::className(), ['order_id' => 'order_id']);
    }

    public function getCost()
    {
        $total = 0;
        foreach($this->goods as $good){
            $total += $good->count * round($good->price * (1 - $good->discount / 100));
        }

        return $total;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert) {
                $this->ip = Yii::$app->request->userIP;
                $this->access_token = Yii::$app->security->generateRandomString(32);
                $this->time = time();
            } else {
                if($this->oldAttributes['status'] == self::STATUS_BLANK && $this->status == self::STATUS_PENDING){
                    $this->new = 1;
                    $this->mailAdmin();
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach($this->getGoods()->all() as $good){
            $good->delete();
        }
    }

    public function mailAdmin()
    {
        $settings = Yii::$app->getModule('admin')->getModule('shopcart')->settings;

        if(!$settings['mailAdminOnNewOrder']){
            return false;
        }
        return Mail::send(
            Setting::get('admin_email'),
            $settings['subjectOnNewOrder'],
            $settings['templateOnNewOrder'],
            [
                'order' => $this,
                'link' => Url::to(['/admin/shopcart/a/view', 'id' => $this->primaryKey], true)
            ]
        );
    }

    public function notifyUser()
    {
        $settings = Yii::$app->getModule('admin')->getModule('shopcart')->settings;

        return Mail::send(
            $this->email,
            $settings['subjectNotifyUser'],
            $settings['templateNotifyUser'],
            [
                'order' => $this,
                'link' => Url::to([$settings['frontendShopcartRoute'], 'id' => $this->primaryKey, 'token' => $this->access_token], true)
            ]
        );
    }
}