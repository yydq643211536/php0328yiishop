<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use backend\models\GoodsCategoryQuqey;
use yii\web\HttpException;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $models = GoodsCategory::find()->orderBy('tree,lft')->all();
        return $this->render('index',['models'=>$models]);
    }

    public function actionAdd()
    {
        $model = new GoodsCategory();

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断是否是添加一级分类
            if($model->parent_id){
                //非一级分类
                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($category){
                    $model->prependTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }

            }else{
                //一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','分类添加成功');
            return $this->redirect(['index']);
        }

        return $this->render('add',['model'=>$model]);
    }

    //测试嵌套集合插件的用法
    public function actionTest()
    {
        //创建一个根节点
        $category = new GoodsCategory();
        $category->name = '手机';
//
        $category->makeRoot();
        //创建子节点
//        $category2 = new GoodsCategory();
//        $category2->name = '小家电';
//        $category = GoodsCategory::findOne(['id'=>1]);
//        $category2->parent_id = $category->id;
//        $category2->prependTo($category);
    }

    //测试zTree
    public function actionZtree()
    {
        //不加载布局文件
        return $this->renderPartial('ztree');
    }

    public function actionAdd2()
    {
        $model = new GoodsCategory(['parent_id'=>0]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $name = GoodsCategory::findAll(['name'=>$model->name]);
            if($name){
                throw new HttpException(404,'同级分类下不能出现相同名字的分类');
            }
            //判断是否是添加一级分类
            if($model->parent_id){
                //非一级分类
                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($category){
                    $model->prependTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }

            }else{
                //一级分类
                $model->makeRoot();
            }

//            $parent_id = 'parent_id'=$model->parent_id;
//            var_dump($parent_id);exit;
//            $name = 'name'=$model->name;
            \Yii::$app->session->setFlash('success','分类添加成功');
            return $this->redirect(['index']);
        }
        //获取所有分类数据
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add2',['model'=>$model,'categories'=>$categories]);
    }

    public function actionEdit($id)
    {
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new HttpException('分类不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //$model->save();
            //判断是否是添加一级分类
            if($model->parent_id){
                //非一级分类

                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($category){
                    $model->appendTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }

            }else{
                //一级分类
                //bug fix:修复根节点修改为根节点的bug
                if($model->oldAttributes['parent_id']==0){
                    $model->save();
                }else{
                    $model->makeRoot();
                }

            }
            \Yii::$app->session->setFlash('success','分类修改成功');
            return $this->redirect(['index']);

        }
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();

        return $this->render('add2',['model'=>$model,'categories'=>$categories]);

    }

    public function actionDelete($id)
    {
        $model = GoodsCategory::findOne($id);
        $children = $model->children()->all();

        if($children){
            \yii::$app->session->setFlash('success','下级还有分类,删除失败');
        }else{
            \yii::$app->session->setFlash('warning','删除成功');
        }
        return $this->redirect(['index']);
    }

}
