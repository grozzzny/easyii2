<?php
namespace yii\easyii2\behaviors;

use Yii;
use yii\easyii2\components\ActiveRecord;
use yii\easyii2\components\Controller;

/**
 * Status behavior. Adds statuses to models
 * @package yii\easyii2\behaviors
 */
class StatusController extends \yii\base\Behavior
{
    public $model;

    public function changeStatus($id, $status)
    {
        /* @var ActiveRecord $modelClass*/
        $modelClass = $this->model;

        /* @var Controller $controller*/
        $controller = $this->owner;

        if(($model = $modelClass::findOne($id))){
            $model->status = $status;
            $model->update();
        }
        else{
            $controller->error = Yii::t('easyii2', 'Not found');
        }

        return $controller->formatResponse(Yii::t('easyii2', 'Status successfully changed'));
    }
}