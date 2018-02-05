<?php
namespace yii\easyii2\modules\file\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use yii\easyii2\components\Controller;
use yii\easyii2\modules\file\models\File;
use yii\easyii2\helpers\Upload;
use yii\easyii2\behaviors\SortableController;

class AController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => SortableController::className(),
                'model' => File::className()
            ],
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => File::find()->sort(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate($slug = null)
    {
        $model = new File;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(($fileInstanse = UploadedFile::getInstance($model, 'file')))
                {
                    $model->file = $fileInstanse;
                    if($model->validate(['file'])){
                        $model->file = Upload::file($fileInstanse, 'files', false);
                        $model->size = $fileInstanse->size;

                        if($model->save()){
                            $this->flash('success', Yii::t('easyii2/file', 'File created'));
                            return $this->redirect(['/admin/'.$this->module->id]);
                        }
                        else{
                            $this->flash('error', Yii::t('easyii2', 'Create error. {0}', $model->formatErrors()));
                        }
                    }
                    else {
                        $this->flash('error', Yii::t('easyii2/file', 'File error. {0}', $model->formatErrors()));
                    }
                }
                else {
                    $this->flash('error', Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('file')]));
                }
                return $this->refresh();
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
        $model = File::findOne($id);

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
                if(($fileInstanse = UploadedFile::getInstance($model, 'file')))
                {
                    $model->file = $fileInstanse;
                    if($model->validate(['file'])){
                        $model->file = Upload::file($fileInstanse, 'files', false);
                        $model->size = $fileInstanse->size;
                        $model->time = time();
                    }
                    else {
                        $this->flash('error', Yii::t('easyii2/file', 'File error. {0}', $model->formatErrors()));
                        return $this->refresh();
                    }
                }
                else{
                    $model->file = $model->oldAttributes['file'];
                }

                if($model->save()){
                    $this->flash('success', Yii::t('easyii2/file', 'File updated'));
                }
                else {
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
        if(($model = File::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('easyii2', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii2/file', 'File deleted'));
    }

    public function actionUp($id)
    {
        return $this->move($id, 'up');
    }

    public function actionDown($id)
    {
        return $this->move($id, 'down');
    }
}