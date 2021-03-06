<?php
namespace yii\easyii2\modules\gallery\api;

use yii\data\ActiveDataProvider;
use yii\easyii2\components\ActiveRecord;
use yii\easyii2\components\API;
use yii\easyii2\models\Photo;
use yii\easyii2\modules\gallery\models\Category;
use yii\helpers\Url;
use yii\widgets\LinkPager;

class CategoryObject extends \yii\easyii2\components\ApiObject
{
    /**
     * @var Category
     */
    public $model;

    public $slug;
    public $image;
    public $tree;
    public $depth;

    private $_adp;
    private $_photos;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function pages($options = []){
        return $this->_adp ? LinkPager::widget(array_merge($options, ['pagination' => $this->_adp->pagination])) : '';
    }

    public function getPagination(){
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function photos($options = [])
    {
        if(!$this->_photos){
            $this->_photos = [];
            $modelPhoto = ActiveRecord::getModelByName('Photo', 'admin');
            $query = $modelPhoto::find()->where(['class' => Category::className(), 'item_id' => $this->id])->sort();

            if(!empty($options['where'])){
                $query->andFilterWhere($options['where']);
            }

            $this->_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => !empty($options['pagination']) ? $options['pagination'] : []
            ]);

            foreach($this->_adp->models as $model){
                $this->_photos[] = new PhotoObject($model);
            }
        }
        return $this->_photos;
    }

    public function getEditLink(){
        return Url::to(['/admin/gallery/a/edit/', 'id' => $this->id]);
    }
}