<?php
namespace yii\easyii2\models;

/**
 * Interface ModuleDBEasyii2Interface
 * @package yii\easyii2\models
 *
 * @property \yii\easyii2\models\Module $model
 */
interface ModuleDBEasyii2Interface extends ModuleEasyii2Interface
{
    /**
     * @return \yii\easyii2\models\Module
     */
    public function getModel();
}