<?php
namespace yii\easyii2\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\easyii2\components\ActiveRecord;
use yii\easyii2\models\Photo;

class Photos extends Widget
{
    public $model;

    public function init()
    {
        parent::init();

        if (empty($this->model)) {
            throw new InvalidConfigException('Required `model` param isn\'t set.');
        }
    }

    public function run()
    {
        $modelPhoto = ActiveRecord::getModelByName('Photo', 'admin');
        $photos = $modelPhoto::find()->where(['class' => get_class($this->model), 'item_id' => $this->model->primaryKey])->sort()->all();
        echo $this->render('photos', [
            'photos' => $photos
        ]);
    }

}