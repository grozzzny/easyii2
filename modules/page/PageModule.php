<?php
namespace yii\easyii2\modules\page;

use Yii;
use yii\easyii2\models\ModuleEasyii2Interface;

class PageModule extends \yii\easyii2\components\Module implements ModuleEasyii2Interface
{
    public $title = 'Pages';
    public $icon = 'file';

    public $settings = [
        'modelPage' => '\yii\easyii2\modules\page\models\Page',
    ];

    public function getTitle()
    {
        // TODO: Implement getTitle() method.
        return Yii::t('app', $this->title);
    }

    public function getName()
    {
        // TODO: Implement getName() method.
        return $this->id;
    }

    public function getIcon()
    {
        // TODO: Implement getIcon() method.
        return $this->icon;
    }
}