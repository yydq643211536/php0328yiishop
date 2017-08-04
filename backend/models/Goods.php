<?php

namespace backend\models;

use backend\models\Brand;
use backend\models\GoodsCategory;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 * @property integer $view_times
 */
class Goods extends \yii\db\ActiveRecord
{
    public static $sale_opt=['1'=>'在售','0'=>'下架'];
    public static $status_opt=['1'=>'正常','0'=>'回收站'];
    public $keyword;//关键词
    public $goods_sn;
    public $price_small;
    public $price_big;
    public $goods_sale;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }
    public function getGoodsCategory(){
        //商品分类
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
    public function getBrand(){
        //商品品牌
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'goods_category_id', 'market_price', 'shop_price', 'stock', 'is_on_sale', 'sort'], 'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'create_time', 'view_times'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name','keyword','goods_sn','price_small','price_big','goods_sale'], 'string', 'max' => 50],
            [['sn'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
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
            'sn' => '商品货号',
            'logo' => '商品LOGO',
            'goods_category_id' => '商品分类',
            'brand_id' => '商品品牌',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '商品状态',
            'sort' => '商品排序',
            'create_time' => '添加时间',
            'view_times' => '浏览次数',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    // 创建之前
                    ActiveRecord::EVENT_BEFORE_INSERT => 'create_time',
                ],
            ]
        ];
    }

    //获取所有的商品分类
    public static function getGoodsCategorys(){
        return ArrayHelper::map(GoodsCategory::find()->orderBy('tree','lft')->all(),'id','name');
    }
    //获取素有的品牌分类
    public static function getBrands(){
        return ArrayHelper::map(Brand::find()->all(),'id','name');
    }

    /*
     * 商品和相册关系 1对多
     */
    public function getGalleries()
    {
        return $this->hasMany(GoodsGallery::className(),['goods_id'=>'id']);
    }


    //获取图片轮播数据
    public function getPics()
    {
        $images = [];
        foreach ($this->galleries as $img){
            $images[] = Html::img($img->path);
        }
        return $images;
    }

    /*
     * 获取商品详情
     */
    public function getGoodsIntro()
    {
        return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id']);
    }

}
