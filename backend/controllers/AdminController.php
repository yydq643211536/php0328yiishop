<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\ChpwForm;
use backend\models\LoginForm;
use backend\models\PasswordForm;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\web\Request;

class AdminController extends \yii\web\Controller
{
    public function actionLogin()
    {
        $model = new LoginForm();

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->login()){
                //登录成功
                \Yii::$app->session->setFlash('success','登录成功');

                return $this->redirect(['index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }
    public function actionIndex()
    {
        $query = Admin::find();
        //总条数
        $total = $query->count();
//        var_dump($total);exit;
//        每页显示条数
        $perPage = 3;

        //分页工具类
        $pager = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage
        ]);



        $models = $query->limit($pager->limit)->offset($pager->offset)->all();

        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }


    public function actionAdd()
    {
        $admin = new Admin();

        if($admin->load(\Yii::$app->request->post()) && $admin->validate()){
                $admin->password_hash = \Yii::$app->security->generatePasswordHash($admin->password);
                $admin->save();
                $authManager = \Yii::$app->authManager;
                $authManager->revokeAll($admin->id);
                if(is_array($admin->roles)){
                    foreach ($admin->roles as $roleName){
                        $role = $authManager->getRole($roleName);
                        if($role) $authManager->assign($role,$admin->id);
                    }
                }
                \Yii::$app->session->setFlash('success','用户添加成功');
                return $this->redirect(['index']);
            }


        return $this->render('add',['admin'=>$admin]);
    }

    public function actionEdit($id)
    {
//        $admin = new Admin();
        $admin = Admin::findOne(['id'=>$id]);
        $request = new Request();

        if($request->isPost){
            $admin->load($request->post());
            if ($admin->password_hash) {
                $admin->password_hash = \Yii::$app->security->generatePasswordHash($admin->password);
            }
                $admin->save();
                return $this->redirect(['admin/index']);

        }
        return $this->render('add',['admin'=>$admin]);
    }

    public function actionDelete($id)
    {
        $model = Admin::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect(['admin/index']);
    }
    public function actions()
    {
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'maxLength'=>3,
                'minLength'=>3
            ]
        ];
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['admin/index']);
    }

    public function actionUser()
    {
        //可以通过 Yii::$app->user 获得一个 User实例，
//        $user = \Yii::$app->user;

        // 当前用户的身份实例。未认证用户则为 Null 。
        $identity = \Yii::$app->user->identity;
        var_dump($identity);

//
//        // 当前用户的ID。 未认证用户则为 Null 。
//        $id = \Yii::$app->user->id;
//        var_dump($id);
        // 判断当前用户是否是游客（未认证的）
        var_dump(\Yii::$app->user->isGuest);
//        var_dump($isGuest);
    }

//    public function actionChpw()
//    {
////        $model = new ChpwForm();
////        if(\Yii::$app->user->isGuest==true){
////            return $this->redirect(['admin/login']);
////        }else{
////
////            return $this->render('chpw',['model'=>$model]);
//
//
//        if($admin = \Yii::$app->user->identity){
//            $model = new ChpwForm();
//            if($model->load(\Yii::$app->request->post()) && $model->validate()){
//            if(\Yii::$app->security->validatePassword($model->old_password,$admin->password_hash)){
//                if(\Yii::$app->security->validatePassword($model->new_password,$admin->password_hash)){
//                    \yii::$app->session->setFlash('success','密码修改成功');
//                    $admin->password_hash = \Yii::$app->security->generatePasswordHash($model->new_password);
//                    $admin->save();
//                    return $this->redirect(['index']);
//                }else{
//                    $model->addError('new_password','新密码和旧密码不能相同');
//                }
//            }else{
//                //提示旧密码不正确
//                $model->addError('old_password','旧密码不正确');
//            }
//            }
//            return $this->render('chpw',['model'=>$model]);
//        }
//        return $this->redirect(['login']);
//        }


    public function actionChPw(){
        //表单字段  旧密码 新密码 确认新密码
        //验证规则  都不能为空  验证旧密码是否正确  新密码不能和旧密码一样  确认新密码和新密码一样
        //表单验证通过 更新新密码
        $model = new PasswordForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //验证通过，更新新密码
            $admin=\Yii::$app->user->identity;

            //保存密码
            $admin->save();


            \Yii::$app->session->setFlash('success','密码修改成功');
            return $this->redirect(['index']);
        }

        return $this->render('password',['model'=>$model]);
    }

}
