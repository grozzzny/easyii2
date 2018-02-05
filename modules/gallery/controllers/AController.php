<?php
namespace yii\easyii2\modules\gallery\controllers;

use yii\easyii2\components\CategoryController;
use yii\easyii2\modules\gallery\models\Category;

class AController extends CategoryController
{
    public $categoryClass = 'yii\easyii2\modules\gallery\models\Category';
    public $moduleName = 'gallery';
    public $viewRoute = '/a/photos';

    public function actionPhotos($id)
    {
        if(!($model = Category::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('photos', [
            'model' => $model,
        ]);
    }
}