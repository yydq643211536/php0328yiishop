<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'oldPassword')->passwordInput();
echo $form->field($model,'newPassword')->passwordInput();
echo $form->field($model,'rePassword')->passwordInput();
echo \yii\helpers\Html::submitButton('修改',['class'=>'btn btn-sm btn-warning']);

\yii\bootstrap\ActiveForm::end();