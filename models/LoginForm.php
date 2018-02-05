<?php

namespace yii\easyii2\models;

use Yii;
use yii\easyii2\components\ActiveRecord;

use yii\easyii2\validators\EscapeValidator;

class LoginForm extends ActiveRecord
{
    const CACHE_KEY = 'SIGNIN_TRIES';

    private $_user = false;

    public static function tableName()
    {
        return 'easyii2_loginform';
    }

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password'], EscapeValidator::className()],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('easyii2', 'Username'),
            'password' => Yii::t('easyii2', 'Password'),
            'remember' => Yii::t('easyii2', 'Remember me')
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('easyii2', 'Incorrect username or password.'));
            }
        }
    }

    public function login()
    {
        $cache = Yii::$app->cache;

        if(($tries = (int)$cache->get(self::CACHE_KEY)) > 5){
            $this->addError('username', Yii::t('easyii2', 'You tried to login too often. Please wait 5 minutes.'));
            return false;
        }

        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $this->time = time();

        if ($this->validate()) {
            $this->password = '******';
            $this->success = 1;
        } else {
            $this->success = 0;
            $cache->set(self::CACHE_KEY, ++$tries, 300);
        }
        $this->insert(false);

        return $this->success ? Yii::$app->user->login($this->getUser(), Setting::get('auth_time') ?: null ) : false;

    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Admin::findByUsername($this->username);
        }

        return $this->_user;
    }
}
