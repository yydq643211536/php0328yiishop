<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "abres".
 *
 * @property integer $id
 * @property string $name
 * @property string $location
 * @property string $detail
 * @property integer $tel
 */
class Abres extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'abres';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tel'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['location', 'detail'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'location' => 'Location',
            'detail' => 'Detail',
            'tel' => 'Tel',
        ];
    }
}
