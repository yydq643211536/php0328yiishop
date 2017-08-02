<?php

namespace backend\models;

use backend\models\GoodsCategory;
use Yii;

/**
 * This is the model class for table "goods_intro".
 *
 * @property integer $goods_id
 * @property string $content
 */
class GoodsIntro extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_intro';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => 'Goods ID',
            'content' => '商品描述',
        ];
    }

    public function getGoodsCategory(){
        //
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }

}
