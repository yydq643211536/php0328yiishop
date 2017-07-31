<?php

namespace frontend\controllers;


use app\models\Address;
use frontend\models\Abres;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;
use yii\helpers\Json;

class MemberController extends \yii\web\Controller
{
    public $layout = true;
    public $enableCsrfValidation = false;
    public function actionIndexMember()
    {
        return $this->render('index-member');
    }
    //用户注册
    public function actionRegistMember()
    {
       $model = new Member();
        return $this->render('regist-member',['model'=>$model]);
    }

    //ajax 处理表单提交的数据
    public function actionAjaxMember()
    {
        $model = new Member();
        if($model->load(\Yii::$app->request->post()) && $model->validate())
        {   $model->password_hash=\Yii::$app->security->generatePasswordHash($model->pwd);
            $model->auth_key = \Yii::$app->security->generateRandomString();
            $model->created_at = time();
            $model->save();
            return Json::encode(['status'=>true,'msg'=>'注册成功']);
        }else{
            return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
        }
    }
    //验证码
    public function actions()
    {
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLongth'=>4,
                'maxLongth'=>4,
            ]
        ];
    }

    //登录
    public function actionLoginMember()
    {
        $model = new LoginForm();
        return $this->render('login-member',['model'=>$model]);
    }

    //ajax 处理登录表单传过来的数据
    public function actionLoginAjaxMember()
    {
        $login = new LoginForm();
        if ($login->load(\Yii::$app->request->post()) && $login->validate())
        {
            $member = Member::findOne(['username' => $login->username]);
                    //\Yii::$app->user->login($member);       //登录
                    \Yii::$app->user->login($member, $login->safe_login ? 3600 * 24 * 30 : 0);
                    $member->last_login_ip = ip2long(\Yii::$app->request->userIP);
                    $member->last_login_time = time();
                    $member->save(false);
                    return Json::encode(['status' => true, 'msg' => '登录成功']);
            }
         else{
             return Json::encode(['status' => false, 'msg' =>$login->getErrors()]);
         }
        //return Json::encode(['status' => false, 'msg' =>'用户名不存在']);

    }

    //修改
    public function actionEditMember($id=1)
    {
        $model = Member::findOne(['id'=>$id]);
        //var_dump($model);exit;
        return $this->render('edit-member',['model'=>$model]);
    }



    //收货地址
    public function actionAdres()
    {
        $model = new Abres();
        return $this->render('adres',['model'=>$model]);

    }
}
