<?php
namespace yii\easyii2\modules\feedback\api;

use Yii;
use yii\easyii2\components\ActiveRecord;
use yii\easyii2\modules\feedback\models\Feedback as FeedbackModel;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii2\widgets\ReCaptcha;


/**
 * Feedback module API
 * @package yii\easyii2\modules\feedback\api
 *
 * @method static string form(array $options = []) Returns fully worked standalone html form.
 * @method static array save(array $attributes) If you using your own form, this function will be useful for manual saving feedback's.
 */

class Feedback extends \yii\easyii2\components\API
{
    const SENT_VAR = 'feedback_sent';

    protected $_defaultFormOptions = [
        'errorUrl' => '',
        'successUrl' => ''
    ];

    public function api_form($options = [])
    {
        $model =  ActiveRecord::getModelByName('Feedback', 'feedback');
        $settings = Yii::$app->getModule('admin')->getModule('feedback')->settings;
        $options = array_merge($this->_defaultFormOptions, $options);

        ob_start();
        $form = ActiveForm::begin([
            'enableClientValidation' => true,
            'action' => Url::to(['/admin/feedback/send'])
        ]);

        echo Html::hiddenInput('errorUrl', $options['errorUrl'] ? $options['errorUrl'] : Url::current([self::SENT_VAR => 0]));
        echo Html::hiddenInput('successUrl', $options['successUrl'] ? $options['successUrl'] : Url::current([self::SENT_VAR => 1]));

        echo $form->field($model, 'name');
        echo $form->field($model, 'email')->input('email');

        if($settings['enablePhone']) echo $form->field($model, 'phone');
        if($settings['enableTitle']) echo $form->field($model, 'title');

        echo $form->field($model, 'text')->textarea();

        if($settings['enableCaptcha']) echo $form->field($model, 'reCaptcha')->widget(ReCaptcha::className());

        echo Html::submitButton(Yii::t('easyii2', 'Send'), ['class' => 'btn btn-primary']);
        ActiveForm::end();

        return ob_get_clean();
    }

    public function api_save($data)
    {
        $model =  ActiveRecord::getModelByName('Feedback', 'feedback');
        $model->setAttributes($data);
        if($model->save()){
            return ['result' => 'success'];
        } else {
            return ['result' => 'error', 'error' => $model->getErrors()];
        }
    }
}