<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

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

}
