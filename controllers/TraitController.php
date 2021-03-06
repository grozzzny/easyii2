<?php
namespace yii\easyii2\controllers;



use Yii;
use yii\base\Model;
use yii\bootstrap\ActiveForm;
use yii\easyii2\components\FastModel;
use yii\easyii2\components\FastModelInterface;
use yii\easyii2\helpers\Image;
use yii\easyii2\helpers\Upload;
use yii\web\Response;
use yii\web\UploadedFile;

trait TraitController
{

    /**
     * Сохранение изображений и файлов. Отличе в методе сохранения
     * @param FastModelInterface|FastModel $model
     */
    public function saveFiles(FastModel &$model)
    {
        foreach ($model->getAttributes() as $attribute => $value){
            if($model->hasValidator('image', $attribute)) {
                $model->$attribute = UploadedFile::getInstance($model, $attribute);
                if($model->$attribute && $model->validate([$attribute])){
                    $model->$attribute = Image::upload($model->$attribute, $model::getSlugModel());
                }
                else{
                    $model->$attribute = $model->isNewRecord ? '' : $model->oldAttributes[$attribute];
                }
            }elseif ($model->hasValidator('file', $attribute)){
                if($fileInstanse = UploadedFile::getInstance($model, $attribute)) {
                    $model->$attribute = Upload::file($fileInstanse, $model::getSlugModel());
                }else{
                    $model->$attribute = $model->isNewRecord ? '' : $model->oldAttributes[$attribute];
                }
            }
        }
    }


    public function performAjaxValidation(Model $model)
    {
        if (\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            \Yii::$app->response->data   = ActiveForm::validate($model);
            \Yii::$app->response->send();
            \Yii::$app->end();
        }
    }
}