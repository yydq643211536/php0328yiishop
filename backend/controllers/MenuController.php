<?php

namespace backend\controllers;

use backend\models\Menu;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $models = Menu::find()->where(['parent_id'=>0])->all();

        return $this->render('index',['models'=>$models]);
    }

    //添加菜单
    public function actionAdd()
    {
        $model = new Menu();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','菜单添加成功');
            return $this->redirect(['index']);
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionEdit($id)
    {
        $model = Menu::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->parent_id && !empty($model->children)){
                $model->addError('parent_id','没有下级菜单了');
            }else{
                $model->save();
            }
            \Yii::$app->session->setFlash('success','菜单修改成功');
            return $this->redirect(['index']);
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionDelete($id)
    {

    }
}
 