<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\NotAcceptableHttpException;

class RbacController extends \yii\web\Controller
{
    //添加权限
    public function actionAddPermission()
    {
        $model = new PermissionForm();
        $model->scenario = PermissionForm::SCENARIO_ADD;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $authManager = \Yii::$app->authManager;
            //创建权限
            $permission = $authManager->createPermission($model->name);
            $permission->description = $model->description;
            //保存到数据表
            $authManager->add($permission);

            \Yii::$app->session->setFlash('success','权限添加成功');
            return $this->redirect(['permission-index']);
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    //修改权限
    public function actionEditPermission($name)
    {
        //检查权限是否存在
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($name);
        if($permission == null){
            throw new NotAcceptableHttpException('权限不存在');
        }
        $model = new PermissionForm();
        if(\Yii::$app->request->isPost){
            if($model->load(\Yii::$app->request->post()) && $model->validate()) {
                    //将表单数据赋值给权限
                $permission->name = $model->name;
                $permission->description = $model->description;
                //更新权限
                $authManager->update($name,$permission);

                \Yii::$app->session->setFlash('success', '权限修改成功');
                return $this->redirect(['permission-index']);
            }
        }else{
            //回显权限数据到表单
            $model->name = $permission->name;
            $model->description = $permission->description;
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    //删除权限
    public function actionDeletePermission($name)
    {
        $authManager = \Yii::$app->authManager;
        //找到该对象
        $permission = $authManager->getPermission($name);
        //删除权限对象
        $authManager->remove($permission);
        \Yii::$app->session->setFlash('success','权限删除成功');
        //跳转
        return $this->redirect(['permission-index']);
    }
    //权限列表
    public function actionPermissionIndex()
    {
        //获取所有权限
        $authManager = \Yii::$app->authManager;
        $models = $authManager->getPermissions();

        return $this->render('permission-index',['models'=>$models]);
    }

    //角色添加
    public function actionAddRole()
    {
        $model = new RoleForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //创建和保存角色

            $authManager = \Yii::$app->authManager;
            $role = $authManager->createRole($model->name);
            $role->description = $model->description;
            $authManager->add($role);
            //给角色赋予权限
            if(is_array($model->permissions)){
                foreach ($model->permissions as $permissionName){
                    $permission = $authManager->getPermission($permissionName);
                    if($permission) $authManager->addChild($role,$permission);
                }
            }
            \Yii::$app->session->setFlash('success', '角色添加成功');
            return $this->redirect(['role-index']);

        }

        return $this->render('add-role',['model'=>$model]);
    }

    public function actionEditRole($name)
    {
        $model = new RoleForm();
        $authManager = \Yii::$app->authManager;
        //获取当前角色
        $role = $authManager->getRole($name);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $authManager->removeChildren($role);//移除当前角色的所有权限
            $role->name = $model->name;
            $role->description = $model->description;
            //保存角色
            $authManager->update($name,$role);
            if(is_array($model->permissions)){
                foreach ($model->permissions as $permissionName){
                    //找到权限
                    $permission = $authManager->getPermission($permissionName);
                    if($permission){
                        //将权限加入
                        $authManager->addChild($role,$permission);
                    }
                }
            }
            \Yii::$app->session->setFlash('success','角色修改成功');
            return $this->redirect(['role-index']);
        }

        //回显角色
        //获取当前角色的所有权限
        $permissions=$authManager->getPermissionsByRole($name);
        $model->name=$role->name;
        $model->description=$role->description;
        //回显权限
        $model->permissions=ArrayHelper::map($permissions,'name','name');
        return $this->render('add-role',['model'=>$model]);
    }
    public function actionDeleteRole($name)
    {
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        $authManager->remove($role);
        return $this->redirect(['role-index']);
    }


    public function actionRoleIndex()
    {
        $authManager = \Yii::$app->authManager;
        $models = $authManager->getRoles();

        return $this->render('role-index',['models'=>$models]);
    }

}
