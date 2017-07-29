<?php
namespace backend\models;

use yii\base\Model;

class PasswordForm extends Model
{
    public $oldPassword;
    public $newPassword;
    public $rePassword;


    public function rules()
    {
        //验证规则  都不能为空  验证旧密码是否正确  新密码不能和旧密码一样  确认新密码和新密码一样
        return [
            //都不能为空
            [['oldPassword','newPassword','rePassword'],'required'],
            //验证旧密码是否正确 自定义验证规则
            ['oldPassword','validatePassword'],
            //新密码不能和旧密码一样
            ['newPassword','compare','compareAttribute'=>'oldPassword','operator'=>'!='],
            //确认新密码和新密码一样
            ['rePassword','compare','compareAttribute'=>'newPassword'],
        ];

    }

    //自定义验证方法
    public function validatePassword()
    {
        //只处理验证不通过的情况，添加相应的错误信息
        //$this->oldPassword
        if(!\Yii::$app->security->validatePassword($this->oldPassword,\Yii::$app->user->identity->password_hash)){

            //密码错误
           $this->addError('oldPassword','旧密码错误');
        }

    }

}
