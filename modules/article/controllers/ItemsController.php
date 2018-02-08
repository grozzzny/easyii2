<?php
namespace yii\easyii2\modules\article\controllers;

use Yii;
use yii\easyii2\behaviors\SortableDateController;
use yii\easyii2\behaviors\StatusController;
use yii\easyii2\components\ActiveRecord;
use yii\web\UploadedFile;

use yii\easyii2\components\Controller;
use yii\easyii2\helpers\Image;
use yii\widgets\ActiveForm;

class ItemsController extends Controller
{
    public function behaviors()
    {
        $model = ActiveRecord::getModelByName('Item', 'article');
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

    public function actionIndex($id)
    {
        $model = ActiveRecord::getModelByName('Category', 'article');
        if(!($model = $model::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }


    public function actionCreate($id)
    {
        $category = ActiveRecord::getModelByName('Category', 'article');
        if(!($category = $category::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        $model = ActiveRecord::getModelByName('Item', 'article');

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
                $model->category_id = $category->primaryKey;

                if (isset($_FILES) && $this->module->settings['articleThumb']) {
                    $model->image = UploadedFile::getInstance($model, 'image');
                    if ($model->image && $model->validate(['image'])) {
                        $model->image = Image::upload($model->image, 'article');
                    } else {
                        $model->image = '';
                    }
                }

                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii2/article', 'Article created'));
                    return $this->redirect(['/admin/'.$this->module->id.'/items/edit', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('easyii2', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('create', [
                'model' => $model,
                'category' => $category,
            ]);
        }
    }

    public function actionEdit($id)
    {
        $model = ActiveRecord::getModelByName('Item', 'article');
        if(!($model = $model::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
                if (isset($_FILES) && $this->module->settings['articleThumb']) {
                    $model->image = UploadedFile::getInstance($model, 'image');
                    if ($model->image && $model->validate(['image'])) {
                        $model->image = Image::upload($model->image, 'article');
                    } else {
                        $model->image = $model->oldAttributes['image'];
                    }
                }

                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii2/article', 'Article updated'));
                    return $this->redirect(['/admin/'.$this->module->id.'/items/edit', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('easyii2', 'Update error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }

    public function actionPhotos($id)
    {
        $model = ActiveRecord::getModelByName('Item', 'article');
        if(!($model = $model::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('photos', [
            'model' => $model,
        ]);
    }

    public function actionClearImage($id)
    {
        $model = ActiveRecord::getModelByName('Item', 'article');
        $model = $model::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii2', 'Not found'));
        }
        elseif($model->image){
            $model->image = '';
            if($model->update()){
                $this->flash('success', Yii::t('easyii2', 'Image cleared'));
            } else {
                $this->flash('error', Yii::t('easyii2', 'Update error. {0}', $model->formatErrors()));
            }
        }
        return $this->back();
    }

    public function actionDelete($id)
    {
        $model = ActiveRecord::getModelByName('Item', 'article');
        if(($model = $model::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('easyii2', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii2/article', 'Article deleted'));
    }

    public function actionUp($id, $category_id)
    {
        return $this->move($id, 'up', ['category_id' => $category_id]);
    }

    public function actionDown($id, $category_id)
    {
        return $this->move($id, 'down', ['category_id' => $category_id]);
    }

    public function actionOn($id)
    {
        $model = ActiveRecord::getModelByName('Item', 'article');
        return $this->changeStatus($id, $model::STATUS_ON);
    }

    public function actionOff($id)
    {
        $model = ActiveRecord::getModelByName('Item', 'article');
        return $this->changeStatus($id, $model::STATUS_OFF);
    }
}