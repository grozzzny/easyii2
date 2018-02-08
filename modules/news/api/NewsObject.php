<?php
namespace yii\easyii2\modules\news\api;

use Yii;
use yii\easyii2\components\ActiveRecord;
use yii\easyii2\components\API;
use yii\easyii2\models\Photo;
use yii\easyii2\modules\news\models\News as NewsModel;
use yii\helpers\Url;

/**
 * Class NewsObject
 * @package yii\easyii2\modules\news\api
 *
 * @property-read PhotoObject[] $photos
 */
class NewsObject extends \yii\easyii2\components\ApiObject
{
    /**
     * @var \yii\easyii2\modules\news\models\News
     */
    public $model;

    public $slug;
    public $image;
    public $views;
    public $time;

    private $_photos;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getShort(){
        return LIVE_EDIT ? API::liveEdit($this->model->short, $this->editLink) : $this->model->short;
    }

    public function getText(){
        return LIVE_EDIT ? API::liveEdit($this->model->text, $this->editLink, 'div') : $this->model->text;
    }

    public function getTags(){
        return $this->model->tagsArray;
    }

    public function getDate(){
        return Yii::$app->formatter->asDate($this->time);
    }

    public function getPhotos()
    {
        if(!$this->_photos){
            $this->_photos = [];
            $model = ActiveRecord::getModelByName('News', 'news');
            foreach(Photo::find()->where(['class' => $model::className(), 'item_id' => $this->id])->sort()->all() as $model){
                $this->_photos[] = new PhotoObject($model);
            }
        }
        return $this->_photos;
    }

    public function  getEditLink(){
        return Url::to(['/admin/news/a/edit/', 'id' => $this->id]);
    }
}