<?php
namespace yii\easyii2\modules\article\api;

use Yii;
use yii\easyii2\components\ActiveRecord;
use yii\easyii2\components\API;
use yii\easyii2\models\Photo;
use yii\easyii2\modules\article\models\Item;
use yii\helpers\Url;

/**
 * Class ArticleObject
 * @package yii\easyii2\modules\article\api
 *
 * @property string $short
 * @property string $title
 * @property string $text
 * @property CategoryObject $cat
 * @property array $tags
 * @property string $date
 * @property PhotoObject[] $photos
 * @property string $editLink
 */
class ArticleObject extends \yii\easyii2\components\ApiObject
{
    /**
     * @var Item
     */
    public $model;

    /** @var  string */
    public $slug;

    public $image;

    public $views;

    public $time;

    /** @var  int */
    public $category_id;

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

    public function getCat(){
        return Article::cats()[$this->category_id];
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
            $model = ActiveRecord::getModelByName('Item', 'article');
            foreach(Photo::find()->where(['class' => $model::className(), 'item_id' => $this->id])->sort()->all() as $model){
                $this->_photos[] = new PhotoObject($model);
            }
        }
        return $this->_photos;
    }

    public function getEditLink(){
        return Url::to(['/admin/article/items/edit/', 'id' => $this->id]);
    }
}