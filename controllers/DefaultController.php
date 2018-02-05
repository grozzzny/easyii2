<?php
namespace yii\easyii2\controllers;

class DefaultController extends \yii\easyii2\components\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}