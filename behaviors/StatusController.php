<?php
namespace yii\easyii2\behaviors;

use Yii;

/**
 * Status behavior. Adds statuses to models
 * @package yii\easyii2\behaviors
 */
class StatusController extends \yii\base\Behavior
{
    public $model;

    public function changeStatus($id, $status)
    {
        $modelClass = $this->model;

        if(($model = $modelClass::findOne($id))){
            $model->status = $status;
            $model->update();
        }
        else{
            $this->error = Yii::t('easyii2', 'Not found');
        }

        return $this->owner->formatResponse(Yii::t('easyii2', 'Status successfully changed'));
    }
}