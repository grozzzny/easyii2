<?php


namespace yii\easyii2\models;

/**
 * Interface ModuleEasyii2Interface
 * @package yii\easyii2\models
 *
 * @property string $title
 * @property string $name
 * @property string $icon
 * @property array $settings
 */
interface ModuleEasyii2Interface
{
    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getIcon();

    /**
     * @return array
     */
    public function getSettings();
}