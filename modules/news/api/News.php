<?php
namespace yii\easyii2\modules\news\api;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii2\components\ActiveRecord;
use yii\easyii2\models\Tag;
use yii\easyii2\widgets\Fancybox;
use yii\widgets\LinkPager;

use yii\easyii2\modules\news\models\News as NewsModel;

/**
 * News module API
 * @package yii\easyii2\modules\news\api
 *
 * @method static NewsObject get(mixed $id_slug) Get news object by id or slug
 * @method static NewsObject[] items(array $options = []) Get list of news as NewsObject objects
 * @method static mixed last(int $limit = 1) Get last news
 * @method static void plugin(array $options = []) Applies FancyBox widget on photos called by box() function
 * @method static string pages(array $options = []) returns pagination html generated by yii\widgets\LinkPager widget.
 * @method static \stdClass pagination() returns yii\data\Pagination object.
 */

class News extends \yii\easyii2\components\API
{
    private $_adp;
    private $_last;
    private $_items;
    private $_item = [];

    public function api_items($options = [])
    {
        if(!$this->_items){
            $this->_items = [];

            $with = ['seo'];
            if(Yii::$app->getModule('admin')->getModule('news')->settings['enableTags']){
                $with[] = 'tags';
            }

            $model = ActiveRecord::getModelByName('News', 'news');
            $modelClassName = $model::className();
            $query = $model::find()->with($with)->status($model::STATUS_ON);

            if(!empty($options['where'])){
                $query->andFilterWhere($options['where']);
            }
            if(!empty($options['tags'])){
                $query
                    ->innerJoinWith('tags', false)
                    ->andWhere([Tag::tableName() . '.name' => (new $modelClassName)->filterTagValues($options['tags'])])
                    ->addGroupBy('news_id');
            }
            if(!empty($options['orderBy'])){
                $query->orderBy($options['orderBy']);
            } else {
                $query->sortDate();
            }

            $this->_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => !empty($options['pagination']) ? $options['pagination'] : []
            ]);

            foreach($this->_adp->models as $model){
                $this->_items[] = new NewsObject($model);
            }
        }
        return $this->_items;
    }

    public function api_get($id_slug)
    {
        if(!isset($this->_item[$id_slug])) {
            $this->_item[$id_slug] = $this->findNews($id_slug);
        }
        return $this->_item[$id_slug];
    }

    public function api_last($limit = 1)
    {
        if($limit === 1 && $this->_last){
            return $this->_last;
        }

        $with = ['seo'];
        if(Yii::$app->getModule('admin')->getModule('news')->settings['enableTags']){
            $with[] = 'tags';
        }

        $result = [];

        $model = ActiveRecord::getModelByName('News', 'news');

        foreach($model::find()->with($with)->status($model::STATUS_ON)->sortDate()->limit($limit)->all() as $item){
            $result[] = new NewsObject($item);
        }

        if($limit > 1){
            return $result;
        } else {
            $this->_last = count($result) ? $result[0] : null;
            return $this->_last;
        }
    }

    public function api_plugin($options = [])
    {
        Fancybox::widget([
            'selector' => '.easyii2-box',
            'options' => $options
        ]);
    }

    public function api_pagination()
    {
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function api_pages($options = [])
    {
        return $this->_adp ? LinkPager::widget(array_merge($options, ['pagination' => $this->_adp->pagination])) : '';
    }

    private function findNews($id_slug)
    {
        $model = ActiveRecord::getModelByName('News', 'news');
        $news = $model::find()->where(['or', 'news_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->status($model::STATUS_ON)->one();
        if($news) {
            $news->updateCounters(['views' => 1]);
            return new NewsObject($news);
        } else {
            return null;
        }
    }
}