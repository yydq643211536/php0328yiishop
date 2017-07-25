<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($admin,'username');
echo $form->field($admin,'password_hash')->passwordInput();
echo $form->field($admin,'email');
//echo $form->field($admin,'status',['inline'=>1])->radioList(\backend\models\Admin::$status_opt);
echo \yii\bootstrap\Html::submitButton('注册',['class'=>'btn btn-sm btn-danger']);

\yii\bootstrap\ActiveForm::end();