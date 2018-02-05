<?php
namespace yii\easyii2\modules\faq\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

use yii\easyii2\components\Controller;
use yii\easyii2\modules\faq\models\Faq;
use yii\easyii2\behaviors\SortableController;
use yii\easyii2\behaviors\StatusController;

class AController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => SortableController::className(),
                'model' => Faq::className()
            ],
            [
                'class' => StatusController::className(),
                'model' => Faq::className()
            ]
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Faq::find()->sort(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate($slug = null)
    {
        $model = new Faq;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', Yii::t('easyii2/faq', 'Entry created'));
                    return $this->redirect(['/admin/'.$this->module->id]);
                }
                else{
                    $this->flash('error', Yii::t('easyii2', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            if($slug) $model->slug = $slug;

            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function actionEdit($id)
    {
        $model = Faq::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii2', 'Not found'));
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if($model->save()){
                    $this->flash('success', Yii::t('easyii2/faq', 'Entry updated'));
                }
                else{
                    $this->flash('error', Yii::t('easyii2', 'Update error. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        }
        else {
            return $this->render('edit', [
                'model' => $model
            ]);
        }
    }

    public function actionDelete($id)
    {
        if(($model = Faq::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('easyii2', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii2/faq', 'Entry deleted'));
    }

    public function actionUp($id)
    {
        return $this->move($id, 'up');
    }

    public function actionDown($id)
    {
        return $this->move($id, 'down');
    }

    public function actionOn($id)
    {
        return $this->changeStatus($id, Faq::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, Faq::STATUS_OFF);
    }
}