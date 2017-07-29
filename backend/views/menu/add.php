<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'label')->textInput(['placeholder'=>'菜单名称']);
echo $form->field($model,'parent_id')->dropDownList(\backend\models\Menu::getMenuOptions(),['prompt' => '=请选择上级菜单=']);
echo $form->field($model,'url')->dropDownList(\backend\models\Menu::getUrlOptions(),['prompt' => '=请选择路由=']);
echo $form->field($model,'sort')->textInput(['placeholder'=>'排序']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
