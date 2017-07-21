<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Article::find()->where(['!=','status','-1'])->orderBy(['sort'=>'desc']);
        $total = $query->count();
        $perPage = 3;
        $pager = new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>$perPage,
        ]);

        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }

    public function actionAdd()
    {
        $model = new Article();
        $model1 = new ArticleDetail();
        $request = new Request();
        if($request->isPost){

            $model->load($request->post());
            $model1->load($request->post());

            if($model->validate() && $model1->validate()){
                $model->create_time = time();
                $model->save();
                $model1->article_id =$model->id;
                $model1->save();
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add',['model'=>$model,'model1'=>$model1]);
    }

    public function actionEdit($id)
    {
        $model = Article::findOne(['id'=>$id]);
        $model1 = ArticleDetail::findOne(['article_id'=>$model->id]);
        $request = new Request();
        if($request->isPost){

            $model->load($request->post());
            $model1->load($request->post());

            if($model->validate()){
                $model->save();
                $model1->save();
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add',['model'=>$model,'model1'=>$model1]);

    }

    public function actionDelete($id)
    {
        $model = Article::findOne(['id'=>$id]);
        $model->status = -1;
        $model->save();
        return $this->redirect(['article/index']);
    }

    public function actionDetails($id)
    {
        $model = Article::findOne(['id'=>$id]);
        $model1 = ArticleDetail::findOne(['article_id'=>$model->id]);

        return $this->render('details',['model'=>$model,'model1'=>$model1]);
    }

    public function actionRecovery()

    {
        $models = Article::find()->where(['=','status','-1'])->all();



        return $this->render('recovery',['models'=>$models]);
    }

    public function actionDel($id)
    {
        $model = Article::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect(['article/recovery']);
    }

    public function actionRec($id)
    {
        $model = Article::findOne(['id'=>$id]);
        $model->status = 0;
        $model->save();
        return $this->redirect(['article/index']);
    }

    public function actionRec1($id)
    {
        $model = Article::findOne(['id'=>$id]);
        $model->status = 1;
        $model->save();
        return $this->redirect(['article/index']);
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://www.baidu.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
//                "imageRoot" => Yii::getAlias("@webroot"),
                "imageRoot" => \Yii::getAlias("@webroot")
            ],
        ]
    ];
}

}
