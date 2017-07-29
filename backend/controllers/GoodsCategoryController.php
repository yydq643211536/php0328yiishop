<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class GoodsCategoryController extends \yii\web\Controller
{
    //添加商品分类
    /*public function actionAdd()
    {
        $model = new GoodsCategory();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //$model->save();
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
    }*/

    //添加商品分类（ztree选择上级分类id）
    public function actionAdd()
    {
        $model = new GoodsCategory(['parent_id' => 0]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //$model->save();
            //判断是否是添加一级分类
            if ($model->parent_id) {
                //非一级分类

                $category = GoodsCategory::findOne(['id' => $model->parent_id]);
                if ($category) {
                    $model->appendTo($category);
                } else {
                    throw new HttpException(404, '上级分类不存在');
                }

            } else {
                //一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success', '分类添加成功');
            return $this->redirect(['index']);

        }
        return $this->render('add2',['model'=>$model]);
    }
    public function actionEdit($id)
    {
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //$model->save();
            //不能移动节点到自己节点下
            /*if($model->parent_id == $model->id){
                throw new HttpException(404,'不能移动节点到自己节点下');
            }*/
            try{
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
                \Yii::$app->session->setFlash('success','分类添加成功');
                return $this->redirect(['index']);
            }catch (Exception $e){
                $model->addError('parent_id',GoodsCategory::exceptionInfo($e->getMessage()));
            }


        }

        return $this->render('add2',['model'=>$model]);
    }

    public function actionIndex()
    {
        $models = GoodsCategory::find()->orderBy('tree ASC,lft ASC')->asArray()->all();
        return $this->render('index',['models'=>$models]);
    }
    //删除商品分类
    public function actionDel($id){
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('商品分类不存在');
        }
        if(!$model->isLeaf()){//判断是否是叶子节点，非叶子节点说明有子分类
            throw new ForbiddenHttpException('该分类下有子分类，无法删除');
        }
        $model->deleteWithChildren();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['index']);
    }

    //测试嵌套集合插件的用法
    public function actionTest()
    {
        //创建一个根节点
        /*$category = new GoodsCategory();
        $category->name = '家用电器';
        $category->makeRoot();*/

        //创建子节点
        /*$category2 = new GoodsCategory();
        $category2->name = '小家电';
        $category = GoodsCategory::findOne(['id'=>1]);
        $category2->parent_id = $category->id;
        $category2->prependTo($category);*/

        //删除节点
        //$cate = GoodsCategory::findOne(['id'=>6])->delete();
        echo '操作完成';
    }

    //测试ztree
    public function actionZtree()
    {
        //$this->layout = false;
        //不加载布局文件
        return $this->renderPartial('ztree');
    }
}
