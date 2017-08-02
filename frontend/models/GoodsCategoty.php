<?php
namespace frontend\models;



use yii\db\ActiveRecord;

class GoodsCategory extends ActiveRecord{
    public function getChildren(){
        return $this->hasMany(GoodsCategory::className(),['parent_id'=>'id']);
    }
    public function getChild(){
        return $this->hasMany(GoodsCategory::className(),['parent_id'=>'id']);
    }
}