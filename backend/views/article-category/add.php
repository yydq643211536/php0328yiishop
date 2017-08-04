<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
//intro	text	简介
echo $form->field($model,'intro')->textarea();
//sort	int(11)	排序
echo $form->field($model,'sort')->textInput(['type'=>'number']);
//echo $form->field($model2,'content')->textInput(['type'=>'number']);
//status	int(2)	状态(-1删除 0隐藏 1正常)
echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\Brand::getStatusOptions());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();