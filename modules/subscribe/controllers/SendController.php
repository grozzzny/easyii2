<?php
namespace yii\easyii2\modules\subscribe\controllers;

use Yii;
use yii\easyii2\components\ActiveRecord;
use yii\easyii2\modules\subscribe\api\Subscribe;
use yii\widgets\ActiveForm;

use yii\easyii2\modules\subscribe\models\Subscriber;

class SendController extends \yii\web\Controller
{
    public function actionIndex()
    {
        /** @var Subscriber $model */
        $model =  ActiveRecord::getModelByName('Subscriber', 'subscribe');
        $request = Yii::$app->request;

        if ($model->load($request->post())) {
            if($request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                $returnUrl = $model->save() ? $request->post('successUrl') : $request->post('errorUrl');
                return $this->redirect($returnUrl);
            }
        }
        else {
            return $this->redirect(Yii::$app->request->baseUrl);
        }
    }

    public function actionUnsubscribe($email)
    {
        if($email && filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            /** @var Subscriber $model */
            $model =  ActiveRecord::getModelByName('Subscriber', 'subscribe');
            $model::deleteAll(['email' => $email]);
            echo '<h1>'.Yii::t('easyii2/subscribe/api', 'You have successfully unsubscribed!').'</h1>';
        }
        else{
            throw new \yii\web\BadRequestHttpException(Yii::t('easyii2/subscribe/api', 'Incorrect E-mail'));
        }
    }
}