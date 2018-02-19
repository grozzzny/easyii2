<?php
namespace yii\easyii2\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii2\behaviors\SortableController;
use yii\easyii2\components\FastModel;
use yii\easyii2\components\FastModule;
use yii\widgets\ActiveForm;

use yii\easyii2\components\Controller;

/**
 * Class FastController
 * @package yii\easyii2\controllers
 */
class FastController extends Controller
{
    use TraitController;

    /**
     * @var FastModule $module
     */
    public $module;

    public function behaviors()
    {
        return [
            [
                'class' => SortableController::className(),
                'model' => $this->module->getModelBySlug(Yii::$app->request->get('slug'))
            ],
        ];
    }

    /**
     * @param null $slug
     * @return string
     */
    public function actionIndex($slug = null)
    {
        $model = $this->module->getModelBySlug($slug);

        $query = $model->find();

        $data = new ActiveDataProvider(['query' => $query]);

        $model->querySort($data);

        $model::queryFilter($query, Yii::$app->request->get());

        return $this->render('@easyii2/views/fast/index', [
            'data' => $data,
            'model' => $model
        ]);
    }


    /**
     * Создать
     * @param $slug
     * @return array|string|\yii\web\Response
     */
    public function actionCreate($slug)
    {
        $model = $this->module->getModelBySlug($slug);

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(!empty($_FILES)){
                    $this->saveFiles($model);
                }

                if($model->save()){
                    $this->flash('success', Yii::t('easyii2', 'Post created'));
                    return $this->redirect(['/admin/'.$this->module->id, 'slug' => $slug]);
                }
                else{
                    $this->flash('error', Yii::t('easyii2', 'Error'));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('@easyii2/views/fast/create', [
                'model' => $model
            ]);
        }
    }


    /**
     * Редактировать
     * @param $id
     * @return array|string|\yii\web\Response
     */
    public function actionEdit($slug, $id)
    {
        $model = $this->module->getModelBySlug($slug);

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
                if(isset($_FILES)){
                    $this->saveFiles($model);
                }

                if($model->save()){
                    $this->flash('success', Yii::t('easyii2', 'Post updated'));
                }
                else{
                    $this->flash('error', Yii::t('easyii2', 'Update error. {0}', $model->formatErrors()));
                }
                return $this->redirect(['/admin/'.$this->module->id, 'slug' => $slug]);
            }
        }
        else {
            return $this->render('@easyii2/views/fast/edit', [
                'model' => $model
            ]);
        }
    }


    public function actionPhotos($slug, $id)
    {
        $model = $this->module->getModelBySlug($slug);

        $model = $model::findOne($id);

        if(!($model)){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('@easyii2/views/fast/photos', [
            'model' => $model,
        ]);
    }

//    public function actionFiles($slug, $id)
//    {
//        $model = BaseModel::getModelBySlug($slug);
//
//        $model = $model::findOne($id);
//
//        if(!($model)){
//            return $this->redirect(['/admin/'.$this->module->id]);
//        }
//
//        $files_model = Yii::createObject(Files::className());
//
//        return $this->render('files', [
//            'model' => $model,
//            'files_model' => $files_model
//        ]);
//    }


//    public function actionUpload($id)
//    {
//        if(Yii::$app->request->isAjax){
//            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//
//            $model = Yii::createObject(Files::className());
//
//            $model->event_id = $id;
//
//            if(isset($_FILES)){
//                $this->saveFiles($model);
//                $model->save(false);
//
//                return [
//                    'result' => 'success'
//                ];
//            }
//
//        }
//    }

//    public function actionFileDelete($id)
//    {
//        $model = Files::findOne($id);
//
//        if($model === null){
//            $this->flash('error', Yii::t('easyii2', 'Not found'));
//        }else{
//            $url = $model->file;
//            if($model->delete()){
//                @unlink(Yii::getAlias('@webroot').$url);
//                $this->flash('success', Yii::t('easyii2', 'File cleared'));
//            } else {
//                $this->flash('error', Yii::t('easyii2', 'Update error. {0}', $model->formatErrors()));
//            }
//        }
//        return $this->back();
//    }

    /**
     * Удалить
     * @param $slug
     * @param $id
     * @return mixed
     */
    public function actionDelete($slug, $id)
    {
        $model = $this->module->getModelBySlug($slug);

        if(($model = $model::findOne($id))){
            $model->delete();
        } else {
            $this->error =  Yii::t('easyii2', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii2', 'Post deleted'));
    }


    /**
     * Удалить изображение
     * @param $attribute
     * @param $slug
     * @param $id
     * @return \yii\web\Response
     */
    public function actionClearFile($attribute, $slug, $id)
    {
        $model = $this->module->getModelBySlug($slug);

        $model = $model::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii2', 'Not found'));
        }else{
            $url = $model->$attribute;
            $model->$attribute = '';
            if($model->update()){
                @unlink(Yii::getAlias('@webroot').$url);
                $this->flash('success', Yii::t('easyii2', 'File cleared'));
            } else {
                $this->flash('error', Yii::t('easyii2', 'Update error. {0}', $model->formatErrors()));
            }
        }
        return $this->back();
    }


    /**
     * Активировать
     * @param $slug
     * @param $id
     * @return mixed
     */
    public function actionOn($slug, $id)
    {
        return $this->changeStatus($slug, $id, FastModel::STATUS_ON);
    }


    /**
     * Деактивировать
     * @param $slug
     * @param $id
     * @return mixed
     */
    public function actionOff($slug, $id)
    {
        return $this->changeStatus($slug, $id, FastModel::STATUS_OFF);
    }

    /**
     * Изменить статус
     * @param $slug
     * @param $id
     * @param $status
     * @return mixed
     */
    public function changeStatus($slug, $id, $status)
    {
        $model = $this->module->getModelBySlug($slug);

        if($model = $model::findOne($id)){
            $model->status = $status;
            $model->update();
        }else{
            $this->error = Yii::t('easyii2', 'Not found');
        }

        return $this->formatResponse(Yii::t('easyii2', 'Status successfully changed'));
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