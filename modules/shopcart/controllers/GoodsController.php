<?php
namespace yii\easyii2\modules\shopcart\controllers;

use Yii;

use yii\easyii2\components\ActiveRecord;
use yii\easyii2\components\Controller;
use yii\easyii2\modules\shopcart\models\Good;

class GoodsController extends Controller
{
    public function actionDelete($id)
    {
        $model = ActiveRecord::getModelByName('Good', 'shopcart');

        if(($model = $model::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('easyii2', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii2/shopcart', 'Order deleted'));
    }
}