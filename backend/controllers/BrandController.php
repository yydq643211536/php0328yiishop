<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class BrandController extends \yii\web\Controller
{

    public function actionIndex()
    {
        $query = Brand::find()->where(['!=','status','-1']);
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
        $model = new Brand();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());

            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
                if($model->imgFile){
                    $d = \Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    if(!is_dir($d)){
                        mkdir($d);
                    }
                    $fileName = '/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);

                    $model->logo = $fileName;
                }

                $model->save();
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionEdit($id)
    {
        $model = Brand::findOne(['id'=>$id]);
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());

            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
                if($model->imgFile){
                    $d = \Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    if(!is_dir($d)){
                        mkdir($d);
                    }
                    $fileName = '/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);

                    $model->logo = $fileName;
                }

                $model->save();
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionDelete($id)
    {
        $model = Brand::findOne(['id'=>$id]);
        $model->status= -1;
        $model->save();
        return $this->redirect(['brand/index']);
    }



    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
//                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
//                'format' => function (UploadAction $action) {
//                    $fileext = $action->uploadfile->getExtension();
//                    $filename = sha1_file($action->uploadfile->tempName);
//                    return "{$filename}.{$fileext}";
//                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
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
//                    $action->output['fileUrl'] = $action->getWebUrl();
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    //将图片上传到七牛云
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    $qiniu->uploadFile($action->getSavePath(),$action->getWebUrl()
                    );
                    $url = $qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl'] = $url;
                },
            ],
        ];
    }

    //测试七牛云文件上传
    public function actionQiniu()
    {

        $config = [
            'accessKey'=>'WeBKvDwvIeTYhAi7ljK2tUfJ8ax6dOo_2bF3bEOS',
            'secretKey'=>'gWmxvz0s1Q9NGpeFk46--CcYmUF7FvupCzyVuTQu',
            'domain'=>'http://otbo6qjwg.bkt.clouddn.com/',
            'bucket'=>'yiishop',
            'area'=>Qiniu::AREA_HUADONG
        ];



        $qiniu = new Qiniu($config);
        $key = 'upload/71/4b/714b666910403b47032de8d5fd0132bbf9aa7a50.jpg';

        //将文件上传到七牛云
        $qiniu->uploadFile(\Yii::getAlias('@webroot').'/upload/71/4b/714b666910403b47032de8d5fd0132bbf9aa7a50.jpg',$key);


        //获取改图片在七牛云的地址
        $url = $qiniu->getLink($key);
        var_dump($url);exit;
    }
}
