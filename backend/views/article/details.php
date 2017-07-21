<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model1,'content')->textarea(['rows'=>16]);
\yii\bootstrap\ActiveForm::end();