<?php
namespace backend\models;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $code;
    public $save_login;
    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['code','captcha','captchaAction'=>'admin/captcha']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'save_login'=>'自动登录'
        ];
    }

    public function login()
    {
        //1.1通过用户名查找用户
        $admin = Admin::findOne(['username'=>$this->username]);
        if($admin){
            //用户存在
            //1.2对比用户密码
            if(\Yii::$app->security->validatePassword($this->password,$admin->password_hash)){
                //密码正确，可以登录
                //2.登录(保存用户信息到session)
                \Yii::$app->user->login($admin, $this->save_login ? 3600 * 24 * 30 : 0);

//                var_dump(\Yii::$app->user->login($admin));exit;
                $admin->last_login_time = time();
                $admin->last_login_ip =\Yii::$app->request->userIP;
                $admin->save();
                return true;
            }else{
                //密码错误，提示错误信息
                $this->addError('password','密码错误');
            }
        }else{
            //用户不存在,提示用户名不存在 错误信息
            $this->addError('username','用户名不存在');
        }
        return false;
    }
}