<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $LOGO
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property string $is_on_sale
 * @property string $status
 * @property integer $sort
 * @property integer $create_time
 * @property integer $view_times
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_category_id', 'brand_id', 'stock', 'sort', 'create_time', 'view_times'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 20],
            [['LOGO'], 'string', 'max' => 255],
            [['is_on_sale', 'status'], 'string', 'max' => 1],
            [['name','status','shop_price','market_price','brand_id','sort'],'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'LOGO' => 'LOGO',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '添加时间',
            'view_times' => '浏览次数',
        ];
    }

    ////商品分类
    public static function goodsCategory()
    {
        return ArrayHelper::map(GoodsCategory::find()->all(),'id','name');
    }

    ////品牌分类
    public static function brand()
    {
        return ArrayHelper::map(Brand::find()->all(),'id','name');
    }



    public static function statusOption()
    {
        $status=['0'=>'回收站','1'=>'正常'];
        return $status;
    }

    public static function asleOption()
    {
        $status=[0=>'下架',1=>'在售'];
        return $status;
    }
}
