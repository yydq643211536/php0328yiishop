<?php

namespace frontend\controllers;


use frontend\models\Address;
use frontend\models\Abres;
use frontend\models\Adres;
use frontend\models\City;
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
        {
            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->pwd);
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
                'minLength'=>4,
                'maxLength'=>4,
            ]
        ];
    }

    //登录
    public function actionLoginMember()
    {
        $model = new LoginForm();
        return $this->render('regist-member',['model'=>$model]);
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

    public function actionAdres()
    {
        $model = new Adres();
        $address = Adres::find()->where(['user_id'=>\Yii::$app->user->getId()])->all();
//        var_dump($address)
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                $user_id = \Yii::$app->user->getId();
                $model->user_id = $user_id;
                $model->save(false);
                return Json::encode(['status'=>true,'mag'=>'添加成功']);
            }else{
                return Json::encode(['status'=>false,'mag'=>$model->getErrors()]);
            }
        }
        return $this->renderPartial('adres',['address'=>$address]);
    }


    public function actionEdit($id)
    {
        $model = Adres::findOne(['id'=>$id]);
        $address = Adres::find()->where(['user_id'=>\Yii::$app->user->getId()])->all();
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                $user_id = \Yii::$app->user->getId();
                $model->user_id = $user_id;
                $model->save(false);
                return Json::encode(['status'=>true,'mag'=>'修改成功']);
            }else{
                return Json::encode(['status'=>false,'mag'=>$model->getErrors()]);
            }
        }
        return $this->renderPartial('edit',['address'=>$address,'model'=>$model]);
    }

    //获取所有的城市
    public function actionCity($pid){
        $citys=City::find()->asArray()->where(['parent_id'=>$pid])->all();
        return Json::encode($citys);
    }

    public function actionUser()
    {
        var_dump(\Yii::$app->user->isGuest);
    }


    //测试发送短信功能
    public function actionTest()
    {
        $code = rand(1000,9999);
        $tel = '15298123246';
        $res = \Yii::$app->sms->setPhoneNumbers($tel)->setTemplateParam(['code'=>$code])->send();
        //将短信验证码保存redis（session，mysql）
        \Yii::$app->session->set('code_'.$tel,$code);
        //验证
        $code2 = \Yii::$app->session->get('code_'.$tel);
        if($code == $code2){

        }

    }

    public function actionSms($tel)
    {
        $code=rand(1000,9999);
        \Yii::$app->sms->setPhoneNumbers($tel)->setTemplateParam(['code'=>$code])->send();
        \Yii::$app->session->set('code_'.$tel,$code);
    }


}
