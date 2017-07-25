<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21
 * Time: 13:09
 */

namespace backend\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;

class GoodsCategoryQuery extends \yii\db\ActiveQuery{

    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }

}
