<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'password_hash')->passwordInput();
echo $form->field($model,'save_login')->checkbox();

//生成验证码
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),['captchaAction'=>'admin/captcha','template'=>'<div class="row"><div class="col-lg-1">{image}</div><div class="col-lg-2">{input}</div></div>'])->label('验证码');
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-sm btn-warning']);
\yii\bootstrap\ActiveForm::end();

