<?php
namespace backend\models;
use yii\base\Model;

class ChpwForm extends Model
{
    public $oldPassword;
    public $newPassword;
    public $rePassword;

    public function rules()
    {
        return [
            //密码都不能为空
            [['oldPassword','newPassword','rePassword'],'required'],
            ////确认新密码和新密码一样
            ['rePassword','compare','compareAttribute' => 'newPassword','message'=>'两次输入的密码不一致！'],
            //新密码不能和旧密码一样
            ['newPassword','compare','compareAttribute'=>'oldPassword','operator'=>'!='],
            //验证旧密码是否正确 自定义验证规则
            ['oldPassword','validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'oldPassword'=>'旧密码',
            'newPassword'=>'新密码',
            'rePassword'=>'确认新密码'
        ];
    }

    public function validatePassword()
    {
        //只处理验证不通过的情况，添加相应的错误信息
        if(!\Yii::$app->security->validatePassword($this->oldPassword,\Yii::$app->user->identity->password_hash)){
            $this->addError('oldPassword','旧密码不正确');
        }
    }
}