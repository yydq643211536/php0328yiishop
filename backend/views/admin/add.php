<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($admin,'username');
echo $form->field($admin,'password')->passwordInput();
echo $form->field($admin,'email');
echo $form->field($admin,'roles',['inline'=>1])->checkboxList(\yii\helpers\ArrayHelper::map(Yii::$app->authManager->getRoles(),'name','description'));
if(!$admin->isNewRecord){
    echo $form->field($admin,'status',['inline'=>1])->radioList(\backend\models\Admin::$status_opt);
}
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-sm btn-danger']);

\yii\bootstrap\ActiveForm::end();