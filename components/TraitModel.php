<?php
namespace yii\easyii2\components;


trait TraitModel
{

    /**
     * Проверяет, имеется ли данный валидатор у атрибута или нет
     * @param $validators
     * @param $attribute
     * @return bool
     */
    public function hasValidator($validators, $attribute)
    {
        $validators = is_array($validators) ? $validators : [$validators];

        foreach ($this->rules() as $rule){
            $attributes = is_array($rule[0]) ? $rule[0] : [$rule[0]];
            if(in_array($attribute, $attributes) && in_array($rule[1], $validators)){
                return true;
            }
        }

        return false;
    }

}