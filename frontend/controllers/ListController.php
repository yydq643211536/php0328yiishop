<?php

namespace frontend\controllers;

class ListController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
