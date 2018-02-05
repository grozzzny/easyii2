<?php
namespace yii\easyii2\modules\page\api;

use Yii;
use yii\easyii2\components\API;
use yii\helpers\Html;
use yii\helpers\Url;

class PageObject extends \yii\easyii2\components\ApiObject
{
    public $slug;

    public function getTitle(){
        if($this->model->isNewRecord){
            return $this->createLink;
        } else {
            return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
        }
    }

    public function getText(){
        if($this->model->isNewRecord){
            return $this->createLink;
        } else {
            return LIVE_EDIT ? API::liveEdit($this->model->text, $this->editLink, 'div') : $this->model->text;
        }
    }

    public function getEditLink(){
        return Url::to(['/admin/page/a/edit/', 'id' => $this->id]);
    }

    public function getCreateLink(){
        return Html::a(Yii::t('easyii2/page/api', 'Create page'), ['/admin/page/a/create', 'slug' => $this->slug], ['target' => '_blank']);
    }
}