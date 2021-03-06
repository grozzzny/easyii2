<?php
namespace yii\easyii2\modules\carousel\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii2\components\ActiveRecord;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use yii\easyii2\components\Controller;
use yii\easyii2\modules\carousel\models\Carousel;
use yii\easyii2\helpers\Image;
use yii\easyii2\behaviors\SortableController;
use yii\easyii2\behaviors\StatusController;


class AController extends Controller
{
    public function behaviors()
    {
        $model = ActiveRecord::getModelByName('Carousel', 'carousel');

        return [
            [
                'class' => SortableController::className(),
                'model' => $model::className()
            ],
            [
                'class' => StatusController::className(),
                'model' => $model::className()
            ]
        ];
    }

    public function actionIndex()
    {
        $model = ActiveRecord::getModelByName('Carousel', 'carousel');

        $data = new ActiveDataProvider([
            'query' => $model::find()->sort(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        /**
         * @var Carousel $model
         */
        $model = ActiveRecord::getModelByName('Carousel', 'carousel');

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(($fileInstanse = UploadedFile::getInstance($model, 'image')))
                {
                    $model->image = $fileInstanse;
                    if($model->validate(['image'])){
                        $model->image = Image::upload($model->image, 'carousel');
                        $model->status = Carousel::STATUS_ON;

                        if($model->save()){
                            $this->flash('success', Yii::t('easyii2/carousel', 'Carousel created'));
                            return $this->redirect(['/admin/'.$this->module->id]);
                        }
                        else{
                            $this->flash('error', Yii::t('easyii2', 'Create error. {0}', $model->formatErrors()));
                        }
                    }
                    else {
                        $this->flash('error', Yii::t('easyii2', 'Create error. {0}', $model->formatErrors()));
                    }
                }
                else {
                    $this->flash('error', Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('image')]));
                }
                return $this->refresh();
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
        $model = ActiveRecord::getModelByName('Carousel', 'carousel');
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
                if(($fileInstanse = UploadedFile::getInstance($model, 'image')))
                {
                    $model->image = $fileInstanse;
                    if($model->validate(['image'])){
                        $model->image = Image::upload($model->image, 'carousel');
                    }
                    else {
                        $this->flash('error', Yii::t('easyii2', 'Update error. {0}', $model->formatErrors()));
                        return $this->refresh();
                    }
                }
                else{
                    $model->image = $model->oldAttributes['image'];
                }

                if($model->save()){
                    $this->flash('success', Yii::t('easyii2/carousel', 'Carousel updated'));
                }
                else{
                    $this->flash('error', Yii::t('easyii2/carousel','Update error. {0}', $model->formatErrors()));
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
        $model = ActiveRecord::getModelByName('Carousel', 'carousel');
        if(($model = $model::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('easyii2', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii2/carousel', 'Carousel item deleted'));
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
        return $this->changeStatus($id, Carousel::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, Carousel::STATUS_OFF);
    }
}