<?php
namespace yii\easyii2\modules\subscribe\api;

use Yii;
use yii\easyii2\components\ActiveRecord;
use yii\easyii2\modules\subscribe\models\Subscriber;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/**
 * Subscribe module API
 * @package yii\easyii2\modules\subscribe\api
 *
 * @method static string form(array $options = []) Returns fully working standalone html form.
 * @method static array save(array $attributes) If you are using your own form, this function will be useful for manual saving of subscribers.
 */

class Subscribe extends \yii\easyii2\components\API
{
    const SENT_VAR = 'subscribe_sent';

    public $_defaultFormOptions = [
        'errorUrl' => '',
        'successUrl' => ''
    ];

    public function api_form($options = [])
    {
        /** @var Subscriber $model */
        $model =  ActiveRecord::getModelByName('Subscriber', 'subscribe');
        $options = array_merge($this->_defaultFormOptions, $options);

        ob_start();

        $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'action' => Url::to(['/admin/subscribe/send']),
            'layout' => 'inline'
        ]);
        echo Html::hiddenInput('errorUrl', $options['errorUrl'] ? $options['errorUrl'] : Url::current([self::SENT_VAR => 0]));
        echo Html::hiddenInput('successUrl', $options['successUrl'] ? $options['successUrl'] : Url::current([self::SENT_VAR => 1]));
        echo $form->field($model, 'email')->input('email', ['placeholder' => 'E-mail']);
        echo Html::submitButton(Yii::t('easyii2/subscribe/api', 'Subscribe'), ['class' => 'btn btn-primary', 'id' => 'subscriber-send']);

        ActiveForm::end();

        return ob_get_clean();
    }

    public function api_save($email)
    {
        /** @var Subscriber $model */
        $model =  ActiveRecord::getModelByName('Subscriber', 'subscribe');
        $model->email = $email;
        if($model->save()){
            return ['result' => 'success', 'error' => false];
        } else {
            return ['result' => 'error', 'error' => $model->getErrors()];
        }
    }
}