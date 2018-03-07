<?php
namespace yii\easyii2\modules\shopcart\controllers;

use Yii;
use yii\data\ActiveDataProvider;

use yii\easyii2\components\ActiveRecord;
use yii\easyii2\components\Controller;
use yii\easyii2\modules\shopcart\models\Good;
use yii\easyii2\modules\shopcart\models\Order;

class AController extends Controller
{
    public $pending = 0;
    public $processed = 0;
    public $sent = 0;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var Good
     */
    private $good;

    public function init()
    {
        parent::init();

        $this->good =  ActiveRecord::getModelByName('Good', 'shopcart');
        $this->order =  ActiveRecord::getModelByName('Order', 'shopcart');

        $model = $this->order;

        $this->pending = $model::find()->status($model::STATUS_PENDING)->count();
        $this->processed = $model::find()->status($model::STATUS_PROCESSED)->count();
        $this->sent = $model::find()->status($model::STATUS_SENT)->count();
    }

    public function actionIndex()
    {
        $model = $this->order;

        return $this->render('index', [
            'data' => new ActiveDataProvider([
                'query' => $model::find()->with('goods')->status($model::STATUS_PENDING)->asc(),
                'totalCount' => $this->pending
            ])
        ]);
    }

    public function actionProcessed()
    {
        $model = $this->order;
        $this->setReturnUrl();
        return $this->render('index', [
            'data' => new ActiveDataProvider([
                'query' => $model::find()->with('goods')->status($model::STATUS_PROCESSED)->asc(),
                'totalCount' => $this->processed
            ])
        ]);
    }

    public function actionSent()
    {
        $model = $this->order;
        $this->setReturnUrl();
        return $this->render('index', [
            'data' => new ActiveDataProvider([
                'query' => $model::find()->with('goods')->status($model::STATUS_SENT)->asc(),
                'totalCount' => $this->sent
            ])
        ]);
    }

    public function actionCompleted()
    {
        $model = $this->order;
        $this->setReturnUrl();
        return $this->render('index', [
            'data' => new ActiveDataProvider([
                'query' => $model::find()->with('goods')->status($model::STATUS_COMPLETED)->desc()
            ])
        ]);
    }

    public function actionFails()
    {
        $model = $this->order;
        $this->setReturnUrl();
        return $this->render('index', [
            'data' => new ActiveDataProvider([
                'query' => $model::find()->with('goods')->where(['in', 'status', [$model::STATUS_DECLINED, $model::STATUS_ERROR, $model::STATUS_RETURNED]])->desc()
            ])
        ]);
    }

    public function actionBlank()
    {
        $model = $this->order;
        $this->setReturnUrl();
        return $this->render('index', [
            'data' => new ActiveDataProvider([
                'query' => $model::find()->with('goods')->status($model::STATUS_BLANK)->desc()
            ])
        ]);
    }

    public function actionView($id)
    {
        $order = $this->order;
        $good = $this->good;
        $request = Yii::$app->request;
        $order = $order::findOne($id);

        if($order === null){
            $this->flash('error', Yii::t('easyii2', 'Not found'));
            return $this->redirect(['/admin/'.$this->module->id]);
        }


        if($request->post('status')){
            $newStatus = $request->post('status');
            $oldStatus = $order->status;

            $order->status = $newStatus;
            $order->remark = filter_var($request->post('remark'), FILTER_SANITIZE_STRING);

            if($order->save()){
                if($newStatus != $oldStatus && $request->post('notify')){
                    $order->notifyUser();
                }
                $this->flash('success', Yii::t('easyii2/shopcart', 'Order updated'));
            }
            else {
                $this->flash('error', Yii::t('easyii2', 'Update error. {0}', $order->formatErrors()));
            }
            return $this->refresh();
        }
        else {
            if ($order->new > 0) {
                $order->new = 0;
                $order->update();
            }

            $goods = $good::find()->where(['order_id' => $order->primaryKey])->with('item')->asc()->all();

            return $this->render('view', [
                'order' => $order,
                'goods' => $goods
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->order;
        if(($model = $model::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('easyii2', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii2/shopcart', 'Order deleted'));
    }
}