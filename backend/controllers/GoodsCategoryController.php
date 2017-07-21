<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use backend\models\GoodsCategoryQuqey;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    //测试嵌套集合插件的用法
    public function actionTest()
    {
        //创建一个根节点
//        $category = new GoodsCategory();
//        $category->name = '家用电器';
//
//        $category->makeRoot();
        //创建子节点
        $category2 = new GoodsCategory();
        $category2->name = '小家电';
        $category = GoodsCategory::findOne(['id'=>1]);
        $category2->parent_id = $category->id; 
        $category2->prependTo($category);
    }

}
