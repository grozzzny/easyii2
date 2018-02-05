<?php
namespace yii\easyii2\models;

class Tag extends \yii\easyii2\components\ActiveRecord
{
    public static function tableName()
    {
        return 'easyii2_tags';
    }

    public function rules()
    {
        return [
            ['name', 'required'],
            ['frequency', 'integer'],
            ['name', 'string', 'max' => 64],
        ];
    }
}