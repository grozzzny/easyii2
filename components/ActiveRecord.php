<?php
namespace yii\easyii2\components;

use yii\easyii2\AdminModule;
use yii\helpers\ArrayHelper;

/**
 * Base active record class for easyii2 models
 * @package yii\easyii2\components
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /** @var string  */
    public static $SLUG_PATTERN = '/^[0-9a-z-]{0,128}$/';

    /**
     * Get active query
     * @return ActiveQuery
     */
    public static function find()
    {
        return new ActiveQuery(get_called_class());
    }

    /**
     * @return self
     */
    public static function getModelByName($name_model, $name_module)
    {
        $adminModule = AdminModule::getInstance();
        $settings = $name_module == $adminModule->id ? $adminModule->settings : $adminModule->getModule($name_module)->settings;
        $class_name = ArrayHelper::getValue($settings, 'model'.$name_model, '');

        return \Yii::createObject($class_name);
    }

    /**
     * Formats all model errors into a single string
     * @return string
     */
    public function formatErrors()
    {
        $result = '';
        foreach($this->getErrors() as $attribute => $errors) {
            $result .= implode(" ", $errors)." ";
        }
        return $result;
    }
}