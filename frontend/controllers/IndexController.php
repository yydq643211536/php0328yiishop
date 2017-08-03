<?php
namespace frontend\controllers;


use frontend\models\GoodsCategory;
use yii\web\Controller;

class IndexController extends Controller{
    public $layout=false;
    public function actionIndex(){
        $goods =GoodsCategory::find()->where(['parent_id'=>0])->all();

      return $this->render('index',['goods'=>$goods]);
    }

}