<?php
namespace yii\easyii2\modules\shopcart\controllers;

use Yii;
use yii\easyii2\components\ActiveRecord;
use yii\easyii2\modules\shopcart\api\Shopcart;
use yii\easyii2\modules\shopcart\models\Order;

class SendController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = ActiveRecord::getModelByName('Order', 'shopcart');
        $request = Yii::$app->request;

        if($model->load($request->post())) {
            $returnUrl = Shopcart::send($model->attributes) ? $request->post('successUrl') : $request->post('errorUrl');
            return $this->redirect($returnUrl);
        } else {
            return $this->redirect(Yii::$app->request->baseUrl);
        }
    }
}