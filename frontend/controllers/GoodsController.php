<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\GoodDayCount;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use flyok666\qiniu\Qiniu;
use flyok666\uploadifive\UploadAction;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class GoodsController extends Controller
{
    ///商品添加
    public function actionAdd()
    {
        $goods = new Goods(); ////实例化表单模型
        $goodsIntro = new GoodsIntro();////商品详情
        $request = new Request();////实例化request
        if($request->isPost)
        {
            //var_dump($request->post());exit;
            $goods->load($request->post());
            $goodsIntro->load($request->post());
            if($goods->validate() && $goodsIntro->validate())
            {
                ///每日添加商品初始数
                $day = date('Ymd');
                $goodsCount = GoodDayCount::findOne(['day'=>$day]);
                if($goodsCount == null)
                {
                    $goodsCount = new GoodDayCount();
                    $goodsCount->day=$day;
                    $goodsCount->count=0;
                    $goodsCount->save();
                }
                /////保存商品信息
                $goods->create_time=time();
                $goods->sn=date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                $goods->save();
                /////保存添加商品数

                ////商品详情
                $goodsIntro->goods_id=$goods->id;
                $goodsIntro->content=$goodsIntro->content;
                $goodsIntro->save();
                $goodsCount->count++;
                $goodsCount->save();
                \Yii::$app->session->setFlash('success','添加商品图片');
                //return $this->redirect(['goods/index']);
               return $this->render('good',['good'=>$goods]);
            }
        }



        //var_dump($request->post());exit;
        $categorys = GoodsCategory::find()->select(['id','name','parent_id'])->all();////商品分类
        return $this->render('add',['goods'=>$goods,'categorys'=>$categorys,'goodsIntro'=>$goodsIntro]);
    }




            ////////商品列表
        public function actionIndex()
            {
                ////分页
                $query = Goods::find()->where(['<>','status','0'])->orderBy(['sort'=>'desc']);
                $total = $query->count();
                $pageSize = 5;
                ////分页工具类
                $pager = new Pagination([
                    'totalCount'=>$total,
                    'defaultPageSize'=>$pageSize
                ]);
                $goods = $query->limit($pager->limit)->offset($pager->offset)->all();
                   //$goods = Goods::find()->orderBy(['sort'=>'desc'])->all();
                    //var_dump($goods);exit;
                   return $this->render('index',['goods'=>$goods,'pager'=>$pager]);

            }

            ///////回收站

    public function actionTrash()
    {
        ////分页
        $query = Goods::find()->where(['<>','status','1'])->orderBy(['sort'=>'desc']);
        $total = $query->count();
        $pageSize = 5;
        ////分页工具类
        $pager = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$pageSize
        ]);
        $goods = $query->limit($pager->limit)->offset($pager->offset)->all();
        //$goods = Goods::find()->orderBy(['sort'=>'desc'])->all();
        //var_dump($goods);exit;
        return $this->render('index',['goods'=>$goods,'pager'=>$pager]);

    }

            /////商品修改
    public function actionEidt($id)
    {
        ///获取到需要修改的数据
        //$goodsIntro = new GoodsIntro();////商品详情
        $goods = Goods::findOne(['id'=>$id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        $goodsIntro = GoodsIntro::findOne(['goods_id'=>$id]);
        ////实例化一个request
        $request = new Request();
        if($request->isPost)
        {
            //var_dump($request->post());exit;
            $goods->load($request->post());
            $goodsIntro->load($request->post());

            if($goods->validate() && $goodsIntro->validate())
            {
                $goods->save();

                ////商品详情
                $goodsIntro->goods_id=$goods->id;
                $goodsIntro->content=$goodsIntro->content;
                $goodsIntro->save();
                return $this->redirect(['goods/index']);
            }
        }
        ////商品分类
        $categorys = GoodsCategory::find()->select(['id','name','parent_id'])->all();////商品分类
        ////视图回显
        return $this->render('add',['goods'=>$goods,'goodsIntro'=>$goodsIntro,'categorys'=>$categorys]);

    }


            ////商品删除
    public function actionDel($id)
    {
        ////获到需要修改的数据
        $good = Goods::findOne(['id'=>$id]);
        if($good==null){
            throw new NotFoundHttpException('商品不存在');
        }
        $good->delete();
        $goodIntro = GoodsIntro::findOne(['goods_id'=>$id]);
        $goodIntro->delete();
      /*  $goodGallery = GoodsGallery::find()->where(['=','goods_id',$id])->all();
        $goodGallery->delete();*/
        return $this->redirect(['goods/index']);
    }


    ////商品图片添加
    public function actionPic($goods_id)
    {
        $model = new GoodsGallery();
        ////获取到需要添加pic的商品
        $good = Goods::findOne(['id'=>$goods_id]);
        ////实例化request
        $request = new Request();
        if($request->isPost)
        {
            $model->load($request->post());
            if($model->validate())
            {
                $model->goods_id=$good->id;
                $model->save();
                return $this->redirect(['goods/index']);
            }
        }
        ////视图添加页面
        return $this->render('picadd',['model'=>$model,'good'=>$good]);
        //var_dump($good);exit;
    }


    /////商品相册
    public function actionPindex($goods_id)
    {
        /////获取到需要展示的商品相册
        //$pics=new GoodsGallery();
       //$pics = GoodsGallery::findOne(['goods_id'=>$goods_id]);
        $pics = GoodsGallery::find()->where(['=','goods_id',$goods_id])->all();
        //echo '<pre>';
        //var_dump($pics);exit;
        return $this->render('pindex',['pics'=>$pics]);
    }

    ////AJAX文件上传

    public function actions() {
        return [
            'captcha' => [
                'class' => CaptchaAction::className(),
                'minLength' => 4,
                'maxLength' => 4,
            ],
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yii2shop.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",//上传保存路径
                "imageRoot" => \yii::getAlias("@webroot"),
            ],
        ],
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    //$p1 = '/goods/'.date('Ymd');
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    /*if(!is_dir($p1)){
                        mkdir($p1);
                    }*/
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    // $action->output['fileUrl'] = $action->getWebUrl();
                    /* $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                     $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                     $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"*/
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);

                    $qiniu->uploadFile($action->getSavePath(),
                        $action->getWebUrl());
                    $url = $qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl'] = $url;
                },
            ],
        ];
    }


}
