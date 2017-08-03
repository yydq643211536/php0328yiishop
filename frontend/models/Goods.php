<?php
namespace frontend\models;



use yii\db\ActiveRecord;

class Goods extends ActiveRecord{


    public function getGallery(){
        return $this->hasMany(GoodsGallery::className(),['goods_id'=>'id']);
    }
    public function getContents(){
        return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id']);
    }
}