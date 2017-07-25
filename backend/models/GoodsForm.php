<?php
namespace backend\models;
use yii\base\Model;

class GoodsForm extends Model{
    //public $keyword;
    public $name;//姓名
    public $age;//年龄

    //指定字段的验证规则
    public function rules()
    {
        return [
            [['name','age'],'required','message'=>'{attribute}必填'],//required 必填，不能为空
            ['age','integer','max'=>200,'min'=>18,'tooSmall'=>'不允许未成年人注册'],//年龄为整数
        ];
    }
}