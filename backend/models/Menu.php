<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property integer $parent_id
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort'], 'integer'],
            [['label', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Label',
            'url' => 'Url',
            'parent_id' => 'Parent ID',
            'sort' => 'Sort',
        ];
    }
    //获取一级菜单选项
    public static function getMenuOptions()
    {
        return ArrayHelper::merge([0=>'顶级菜单'],ArrayHelper::map(self::find()->where(['parent_id'=>0])->asArray()->all(),'id','label'));
    }
    //获取地址选项
    public static function getUrlOptions()
    {
        return  ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','name');
    }

    //获取子菜单
    public function getChildren()
    {
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
