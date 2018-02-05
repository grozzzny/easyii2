<?php
namespace yii\easyii2\models;

use Yii;
use yii\easyii2\validators\EscapeValidator;

class SeoText extends \yii\easyii2\components\ActiveRecord
{
    public static function tableName()
    {
        return 'easyii2_seotext';
    }

    public function rules()
    {
        return [
            [['h1', 'title', 'keywords', 'description'], 'trim'],
            [['h1', 'title', 'keywords', 'description'], 'string', 'max' => 255],
            [['h1', 'title', 'keywords', 'description'], EscapeValidator::className()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'h1' => 'Seo H1',
            'title' => 'Seo Title',
            'keywords' => 'Seo Keywords',
            'description' => 'Seo Description',
        ];
    }

    public function isEmpty()
    {
        return (!$this->h1 && !$this->title && !$this->keywords && !$this->description);
    }
}