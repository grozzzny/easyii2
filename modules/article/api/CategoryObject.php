<?php
namespace yii\easyii2\modules\article\api;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii2\components\ActiveRecord;
use yii\easyii2\components\API;
use yii\easyii2\models\Tag;
use yii\easyii2\modules\article\models\Item;
use yii\easyii2\modules\gallery\models\Category;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * Class CategoryObject
 * @package yii\easyii2\modules\article\api
 *
 * @property string $title
 * @property string $editLink
 */
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
    private $_items;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function pages($options = []){
        return $this->_adp ? LinkPager::widget(array_merge($options, ['pagination' => $this->_adp->pagination])) : '';
    }

    public function pagination(){
        return $this->_adp ? $this->_adp->pagination : null;
    }

    /**
     * @param array $options
     * @return ArticleObject[]
     */
    public function items($options = [])
    {
        if(!$this->_items){
            $this->_items = [];

            $with = ['seo'];
            if(Yii::$app->getModule('admin')->activeModules['article']->settings['enableTags']){
                $with[] = 'tags';
            }

            $model = ActiveRecord::getModelByName('Item', 'article');
            $modelClassName = $model::className();
            $query = $model::find()->with('seo')->where(['category_id' => $this->id])->status(Item::STATUS_ON)->sortDate();

            if(!empty($options['where'])){
                $query->andFilterWhere($options['where']);
            }
            if(!empty($options['tags'])){
                $query
                    ->innerJoinWith('tags', false)
                    ->andWhere([Tag::tableName() . '.name' => (new $modelClassName)->filterTagValues($options['tags'])])
                    ->addGroupBy('item_id');
            }

            $this->_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => !empty($options['pagination']) ? $options['pagination'] : []
            ]);

            foreach($this->_adp->models as $model){
                $this->_items[] = new ArticleObject($model);
            }
        }
        return $this->_items;
    }

    public function getEditLink(){
        return Url::to(['/admin/article/a/edit/', 'id' => $this->id]);
    }
}