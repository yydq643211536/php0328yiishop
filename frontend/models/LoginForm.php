<?php
namespace frontend\models;

use yii\base\Model;
use yii\helpers\Json;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $safe_login;
    public $code;

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'safe_login'=>'自动登录',
            'code' => '验证码',
        ];
    }

    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['safe_login','boolean'],
            ['username','validateName'],
            ['password','validatePassword']
        ];
    }
    public function validateName()
    {
        $member = Member::findOne(['username' => $this->username]);
        if (!$member)         //验证用户是否存在
        {
            return $this->addError('username','用户不存在');
        }
    }
    public function validatePassword()
    {
        $member = Member::findOne(['username' => $this->username]);
        if (!\Yii::$app->security->validatePassword($this->password, $member->password_hash))
        {
            return $this->addError('password','密码不正确');
        }
    }
}