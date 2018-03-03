<?php


namespace yii\easyii2\components;


use yii\data\BaseDataProvider;

/**
 * Interface FastModelInterface
 * @package yii\easyii2\components
 *
 */
interface FastModelInterface
{
    public static function queryFilter(ActiveQuery &$query, $get);
    public static function querySort(BaseDataProvider &$provider);
    public static function getNameModel();
    public static function getSlugModel();
    public function enablePhotoManager();
    public function getPhotos();
}