<?php
namespace yii\easyii2\modules\page\api;

use Yii;
use yii\easyii2\components\ActiveRecord;
use yii\easyii2\modules\page\models\Page as PageModel;
use yii\helpers\Html;

/**
 * Page module API
 * @package yii\easyii2\modules\page\api
 *
 * @method static PageObject get(mixed $id_slug) Get page object by id or slug
 */

class Page extends \yii\easyii2\components\API
{
    private $_pages = [];

    public function api_get($id_slug)
    {
        if(!isset($this->_pages[$id_slug])) {
            $this->_pages[$id_slug] = $this->findPage($id_slug);
        }
        return $this->_pages[$id_slug];
    }

    private function findPage($id_slug)
    {
        $model = ActiveRecord::getModelByName('Page', 'page');
        $page = $model::find()->where(['or', 'page_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one();

        return $page ? new PageObject($page) : $this->notFound($id_slug);
    }

    private function notFound($id_slug)
    {
        $model = ActiveRecord::getModelByName('Page', 'page');
        $model->slug = $id_slug;
        return new PageObject($model);
    }
}