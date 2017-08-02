<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;

class GoodsController extends \yii\web\Controller
{
    public $layout = false;
    public function actionIndex()
    {
        //商品分类详情一级
        $categorys = GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['categorys'=>$categorys]);
    }

    public function actionList($id)
    {
        $cate = GoodsCategory::findOne(['id'=>$id]);
        if($cate->depth ==2){
            $models = Goods::find()->where(['goods_category_id'=>$id])->all();
        }else{
            $ids = $cate->leaves()->asArray()->column();
            $models = Goods::find()->where(['in','goods_category_id',$ids])->all();
        }
//        var_dump($models);exit;
        return $this->render('list',['models'=>$models]);


    }

    public function actionShow($id)
    {
        $goods = Goods::findOne(['id'=>$id]);
        return $this->render('show',['goods'=>$goods]);
    }


}
