<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\LoginForm;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\web\Request;

class AdminController extends \yii\web\Controller
{
    public function actionLogin()
    {
        $model = new LoginForm();

        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->login()){
                //登录成功
                \Yii::$app->session->setFlash('success','登录成功');

                return $this->redirect(['admin/index']);
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
        $request = new Request();

        if($request->isPost){
            $admin->load($request->post());

            if($admin->validate()){
                $admin->password_hash = \Yii::$app->security->generatePasswordHash($admin->password_hash);
                $admin->created_at = time();
                $admin->save();
                return $this->redirect(['admin/index']);
            }else{
                var_dump($admin->getErrors());exit;
            }
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

            if($admin->validate()){
                $admin->password_hash = \Yii::$app->security->generatePasswordHash($admin->password_hash);
                $admin->created_at = time();
                $admin->save();
                return $this->redirect(['admin/index']);
            }else{
                var_dump($admin->getErrors());exit;
            }
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
        $user = \Yii::$app->user;

        // 当前用户的身份实例。未认证用户则为 Null 。
        $identity = \Yii::$app->user->identity;
        var_dump($identity);

        // 当前用户的ID。 未认证用户则为 Null 。
        $id = \Yii::$app->user->id;
        var_dump($id);
        // 判断当前用户是否是游客（未认证的）
        $isGuest = \Yii::$app->user->isGuest;
        var_dump($isGuest);
    }
}
