<h1>添加角色</h1>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description');
echo $form->field($model,'permissions',['inline'=>1])->checkboxList(
    \yii\helpers\ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','description'));
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-sm btn-info']);

\yii\bootstrap\ActiveForm::end();