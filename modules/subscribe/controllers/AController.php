<?php
namespace yii\easyii2\modules\subscribe\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii2\components\ActiveRecord;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use yii\easyii2\components\Controller;
use yii\easyii2\models\Setting;
use yii\easyii2\modules\subscribe\models\Subscriber;
use yii\easyii2\modules\subscribe\models\History;

class AController extends Controller
{
    public function actionIndex()
    {
        /** @var Subscriber $model */
        $model =  ActiveRecord::getModelByName('Subscriber', 'subscribe');
        $data = new ActiveDataProvider([
            'query' => $model::find()->desc(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionHistory()
    {
        $this->setReturnUrl();

        /** @var History $model */
        $model =  ActiveRecord::getModelByName('History', 'subscribe');

        $data = new ActiveDataProvider([
            'query' => $model::find()->desc(),
        ]);
        return $this->render('history', [
            'data' => $data
        ]);
    }

    public function actionView($id)
    {
        /** @var History $model */
        $model =  ActiveRecord::getModelByName('History', 'subscribe');

        $model = $model::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii2', 'Not found'));
            return $this->redirect(['/admin/'.$this->module->id.'/history']);
        }

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionCreate()
    {
        /** @var History $model */
        $model =  ActiveRecord::getModelByName('History', 'subscribe');

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else
            {
                if($model->validate() && $this->send($model)){
                    $this->flash('success', Yii::t('easyii2/subscribe', 'Subscribe successfully created and sent'));
                    return $this->redirect(['/admin/'.$this->module->id.'/a/history']);
                }
                else{
                    $this->flash('error', Yii::t('easyii2', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function actionDelete($id)
    {
        /** @var Subscriber $model */
        $model =  ActiveRecord::getModelByName('Subscriber', 'subscribe');

        if(($model = $model::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('easyii2', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii2/subscribe', 'Subscriber deleted'));
    }

    private function send($model)
    {
        $text = $model->body.
                "<br><br>".
                "--------------------------------------------------------------------------------";

        /** @var Subscriber $model */
        $modelSubscriber =  ActiveRecord::getModelByName('Subscriber', 'subscribe');

        foreach($modelSubscriber::find()->all() as $subscriber){
			$unsubscribeLink = '<br><a href="' . Url::to(['/admin/'.$this->module->id.'/send/unsubscribe', 'email' => $subscriber->email], true) . '" target="_blank">'.Yii::t('easyii2/subscribe', 'Unsubscribe').'</a>';
            if(Yii::$app->mailer->compose()
                ->setFrom(Setting::get('robot_email'))
                ->setTo($subscriber->email)
                ->setSubject($model->subject)
                ->setHtmlBody($text.$unsubscribeLink)
                ->setReplyTo(Setting::get('admin_email'))
                ->send())
            {
                $model->sent++;
            }
        }

        return $model->save();
    }
}
