<?php
namespace yii\easyii2\modules\text\api;

use Yii;
use yii\easyii2\components\ActiveRecord;
use yii\easyii2\components\API;
use yii\easyii2\components\Module;
use yii\easyii2\helpers\Data;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * Text module API
 * @package yii\easyii2\modules\text\api
 *
 * @method static get(mixed $id_slug) Get text block by id or slug
 */
class Text extends API
{
    private $_texts = [];

    public function init()
    {
        parent::init();

        $model = ActiveRecord::getModelByName('Text', 'text');

        $this->_texts = Data::cache($model::CACHE_KEY, 3600, function(){
            $model = ActiveRecord::getModelByName('Text', 'text');
            return $model::find()->asArray()->all();
        });
    }

    public function api_get($id_slug)
    {
        if(($text = $this->findText($id_slug)) === null){
            return $this->notFound($id_slug);
        }
        return LIVE_EDIT ? API::liveEdit($text['text'], Url::to(['/admin/text/a/edit/', 'id' => $text['text_id']])) : $text['text'];
    }

    private function findText($id_slug)
    {
        foreach ($this->_texts as $item) {
            if($item['slug'] == $id_slug || $item['text_id'] == $id_slug){
                return $item;
            }
        }
        return null;
    }

    private function notFound($id_slug)
    {
        $text = '';

        if(!Yii::$app->user->isGuest && preg_match(TextModel::$SLUG_PATTERN, $id_slug)){
            $text = Html::a(Yii::t('easyii2/text/api', 'Create text'), ['/admin/text/a/create', 'slug' => $id_slug], ['target' => '_blank']);
        }

        return $text;
    }
}