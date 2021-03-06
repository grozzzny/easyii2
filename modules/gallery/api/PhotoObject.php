<?php
namespace yii\easyii2\modules\gallery\api;

use Yii;
use yii\easyii2\components\API;
use yii\easyii2\models\Photo;
use yii\helpers\Html;
use yii\helpers\Url;

class PhotoObject extends \yii\easyii2\components\ApiObject
{
    /**
     * @var Photo
     */
    public $model;

    public $image;
    public $description;
    public $rel;

    public function box($width, $height){
        $img = Html::img($this->thumb($width, $height));
        $a = Html::a($img, $this->image, [
            'class' => 'easyii2-box',
            'rel' => 'album-' . ($this->rel ? $this->rel : $this->model->item_id),
            'title' => $this->description,
            'data-fancybox' => 'group',
            'data-caption' => $this->description
        ]);
        //return LIVE_EDIT ? API::liveEdit($a, $this->editLink) : $a;
        return $a;
    }

    public function getEditLink(){
        return Url::to(['/admin/gallery/a/photos', 'id' => $this->model->item_id]).'#photo-'.$this->id;
    }
}