<div class="content fl ml10">
    <div class="address_hd">
        <h3>收货地址薄</h3>

        <?php foreach ($mode as $mode):?>
            <dl>
                <dt><?=$mode->name?> <?=$mode->province?> <?=$mode->city?><?=$mode->county?><?=$mode->tel?></dt>
                <dd><?=\yii\bootstrap\Html::a('删除',['address/delete','id'=>$mode->id],['class'=>'btn btn-danger btn-xs'])?>
                    <?=\yii\bootstrap\Html::a('修改',['address/edit','id'=>$mode->id],['class'=>'btn btn-danger btn-xs'])?>
                    <?=\yii\bootstrap\Html::a('设置为默认地址',['address/moren','id'=>$mode->id],['class'=>'btn btn-danger btn-xs'])?></dd>
            </dl>
        <?php endforeach;?>

    </div>

    <div class="address_bd mt10">
        <h4>新增收货地址</h4>
<!--        <form action="" name="address_form">-->
        <?php $form = \yii\widgets\ActiveForm::begin(['fieldConfig'=>[
            'options'=>[
                'tag'=>'li',
            ],
            'errorOptions'=>[
                'tag'=>'p'
            ]
        ]])?>
        <ul>
            <?=$form->field($model,'name')->textInput(['class'=>'txt']);?>
            <li><label for="">所在地区：</label>
                <?=$form->field($model,'province',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择省=']);?>
                <?=$form->field($model,'city',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择市=']);?>
                <?=$form->field($model,'county',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择县=']);?>
            </li>

            <?=$form->field($model,'detail')->textInput(['class'=>'txt']);?>
            <?=$form->field($model,'tel')->textInput(['class'=>'txt']);?>
            <?=$form->field($model,'is_default')->checkbox();?>
            <li>
                <label for="">&nbsp;</label>
                <input type="submit" name="" class="btn" value="保存" />
            </li>
        </ul>

        <?php \yii\widgets\ActiveForm::end();?>
    </div>

</div>
<!-- 右侧内容区域 end -->
</div>
<?php
$this->registerJsFile('@web/js/address.js');
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    //填充省的数据
    $(address).each(function(){
        //console.log(this.name);
        var option = '<option value="'+this.name+'">'+this.name+'</option>';
        $("#address-province").append(option);
    });
    //切换（选中）省，读取该省对应的市，更新到市下拉框
    $("#address-province").change(function(){
        var province = $(this).val();//获取当前选中的省
        //console.log(province);
        //获取当前省对应的市 数据
        $(address).each(function(){
            if(this.name == province){
                var option = '<option value="">=请选择市=</option>';
                $(this.city).each(function(){
                    option += '<option value="'+this.name+'">'+this.name+'</option>';
                });
                $("#address-city").html(option);
            }
        });
        //将县的下拉框数据清空
        $("#address-county").html('<option value="">=请选择县=</option>');
    });
    //切换（选中）市，读取该市 对应的县，更新到县下拉框
    $("#address-city").change(function(){
        var city = $(this).val();//当前选中的城市
        $(address).each(function(){
            if(this.name == $("#address-province").val()){
                $(this.city).each(function(){
                    if(this.name == city){
                        //遍历到当前选中的城市了
                        var option = '<option value="">=请选择县=</option>';
                        $(this.area).each(function(i,v){
                            option += '<option value="'+v+'">'+v+'</option>';
                        });
                        $("#address-county").html(option);
                    }
                });
            }
        });
    });
JS

));
$js = '';
if($model->province){
    $js .= '$("#address-province").val("'.$model->province.'");';
}
if($model->city){
    $js .= '$("#address-province").change();$("#address-city").val("'.$model->city.'");';
}
if($model->county){
    $js .= '$("#address-city").change();$("#address-county").val("'.$model->county.'");';
}
$this->registerJs($js);

?>