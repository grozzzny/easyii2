<?php
namespace yii\easyii2\modules\news\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii2\behaviors\SortableDateController;
use yii\easyii2\components\ActiveRecord;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use yii\easyii2\components\Controller;
use yii\easyii2\modules\news\models\News;
use yii\easyii2\helpers\Image;
use yii\easyii2\behaviors\StatusController;

class AController extends Controller
{
    public function behaviors()
    {
        $model = ActiveRecord::getModelByName('News', 'news');
        return [
            [
                'class' => SortableDateController::className(),
                'model' => $model::className(),
            ],
            [
                'class' => StatusController::className(),
                'model' => $model::className()
            ]
        ];
    }

    public function actionIndex()
    {
        $model = ActiveRecord::getModelByName('News', 'news');
        $data = new ActiveDataProvider([
            'query' => $model::find()->sortDate(),
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = ActiveRecord::getModelByName('News', 'news');
        $model->time = time();

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(isset($_FILES) && $this->module->settings['enableThumb']){
                    $model->image = UploadedFile::getInstance($model, 'image');
                    if($model->image && $model->validate(['image'])){
                        $model->image = Image::upload($model->image, 'news');
                    }
                    else{
                        $model->image = '';
                    }
                }
                if($model->save()){
                    $this->flash('success', Yii::t('easyii2/news', 'News created'));
                    return $this->redirect(['/admin/'.$this->module->id]);
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

    public function actionEdit($id)
    {
        $model = ActiveRecord::getModelByName('News', 'news');
        $model = $model::findOne($id);

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
                if(isset($_FILES) && $this->module->settings['enableThumb']){
                    $model->image = UploadedFile::getInstance($model, 'image');
                    if($model->image && $model->validate(['image'])){
                        $model->image = Image::upload($model->image, 'news');
                    }
                    else{
                        $model->image = $model->oldAttributes['image'];
                    }
                }

                if($model->save()){
                    $this->flash('success', Yii::t('easyii2/news', 'News updated'));
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

    public function actionPhotos($id)
    {
        $model = ActiveRecord::getModelByName('News', 'news');
        if(!($model = $model::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('photos', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = ActiveRecord::getModelByName('News', 'news');
        if(($model = $model::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('easyii2', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii2/news', 'News deleted'));
    }

    public function actionClearImage($id)
    {
        $model = ActiveRecord::getModelByName('News', 'news');
        $model = $model::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii2', 'Not found'));
        }
        else{
            $model->image = '';
            if($model->update()){
                @unlink(Yii::getAlias('@webroot').$model->image);
                $this->flash('success', Yii::t('easyii2', 'Image cleared'));
            } else {
                $this->flash('error', Yii::t('easyii2', 'Update error. {0}', $model->formatErrors()));
            }
        }
        return $this->back();
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
        $model = ActiveRecord::getModelByName('News', 'news');
        return $this->changeStatus($id, $model::STATUS_ON);
    }

    public function actionOff($id)
    {
        $model = ActiveRecord::getModelByName('News', 'news');
        return $this->changeStatus($id, $model::STATUS_OFF);
    }
}