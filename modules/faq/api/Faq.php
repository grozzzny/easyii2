<?php
namespace yii\easyii2\modules\faq\api;

use Yii;
use yii\easyii2\helpers\Data;
use yii\easyii2\modules\faq\models\Faq as FaqModel;


/**
 * FAQ module API
 * @package yii\easyii2\modules\faq\api
 *
 * @method static array items() list of all FAQ as FaqObject objects
 */

class Faq extends \yii\easyii2\components\API
{
    public function api_items()
    {
        return Data::cache(FaqModel::CACHE_KEY, 3600, function(){
            $items = [];
            foreach(FaqModel::find()->select(['faq_id', 'question', 'answer'])->status(FaqModel::STATUS_ON)->sort()->all() as $item){
                $items[] = new FaqObject($item);
            }
            return $items;
        });
    }
}