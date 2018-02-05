<?php
namespace yii\easyii2\controllers;

use yii\data\ActiveDataProvider;

use yii\easyii2\models\LoginForm;

class LogsController extends \yii\easyii2\components\Controller
{
    public $rootActions = 'all';

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => LoginForm::find()->desc(),
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }
}