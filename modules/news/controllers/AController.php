<?php
namespace yii\easyii2\modules\news\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii2\behaviors\SortableDateController;
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
        return [
            [
                'class' => SortableDateController::className(),
                'model' => News::className(),
            ],
            [
                'class' => StatusController::className(),
                'model' => News::className()
            ]
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => News::find()->sortDate(),
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = new News;
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
        $model = News::findOne($id);

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
        if(!($model = News::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('photos', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        if(($model = News::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('easyii2', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii2/news', 'News deleted'));
    }

    public function actionClearImage($id)
    {
        $model = News::findOne($id);

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
        return $this->changeStatus($id, News::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, News::STATUS_OFF);
    }
}