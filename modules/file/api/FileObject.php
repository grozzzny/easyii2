<?php
namespace yii\easyii2\modules\file\api;

use Yii;
use yii\easyii2\components\API;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class FileObject
 * @package yii\easyii2\modules\file\api
 *
 * @property string $title
 * @property string $file
 * @property string $link
 * @property string $bytes
 * @property string $size
 * @property string $date
 * @property string $editLink
 */
class FileObject extends \yii\easyii2\components\ApiObject
{
    /**
     * @var File
     */
    public $model;

    public $slug;
    public $downloads;
    public $time;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getFile(){
        return Url::to(['/admin/file/download', 'id' => $this->id]);
    }

    public function getLink(){
        return Html::a($this->title, $this->file, ['target' => '_blank']);
    }

    public function getBytes(){
        return $this->model->size;
    }

    public function getSize(){
        return Yii::$app->formatter->asShortSize($this->model->size, 2);
    }

    public function getDate(){
        return Yii::$app->formatter->asDatetime($this->time, 'medium');
    }

    public function  getEditLink(){
        return Url::to(['/admin/file/a/edit/', 'id' => $this->id]);
    }
}