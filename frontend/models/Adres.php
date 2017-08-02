<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "adres".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $detail
 * @property string $tel
 * @property integer $status
 */
class Adres extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adres';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','tel','province','city','area','detail'],'required'],
            [['user_id', 'status'], 'integer'],
            [['name', 'province', 'city', 'area', 'detail', 'tel'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户id',
            'name' => '姓名',
            'province' => '省',
            'city' => '市',
            'area' => '区/县',
            'detail' => '详细地址',
            'tel' => '电话号码',
            'status' => '设为默认地址',
        ];
    }
}
