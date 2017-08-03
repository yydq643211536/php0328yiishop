
<div class="header w990 bc mt15">
    <div class="logo w990">

        <!-- 页面头部 end -->
        <h2 class="fl"><a href="index.html"><?=\yii\helpers\Html::img('@web/images/logo.png')?></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <p>
                    <?php foreach($address as $address):?>
                    <input type="radio" <?=$address->status==1?'checked':''?> value="<?=$address->id?> " name="address_id"/> <?=$address->name?> <?=$address->province?> <?=$address->city?> <?=$address->area?> <?=$address->detail?> <?=$address->tel?></p>

                <?php endforeach;?>
            </div>

        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">

        </div>
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($delivery as $k=>$delivery):?>
<!--                        --><?php //var_dump($delivery)?>
                    <tr class="  <?=$k==0?"cur":""?>">
                        <td>
                            <input class="jia" type="radio" name="delivery" <?=$k==0?'checked':''?> value="<?=$delivery['id']?>"/>
                           <?=$delivery['kd']?>


                        </td>
                        <td class="jia_i">    <?=$delivery['jq']?></td>
                        <td>    <?=$delivery['xq']?></td>
                    </tr>
                    <?php endforeach;?>

                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php foreach($pay as $k=>$pay):?>
                    <tr class=" <?=$k==0?"cur":""?>">
                        <td class="col1"><input type="radio" name="pay" <?=$k==0?'checked':''?> value="<?=$pay['id']?>"/>   <?=$pay['kd']?></td>
                        <td class="col2"><?=$pay['xq']?></td>
                    </tr>

                    <?php endforeach;?>


                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>

            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php $a=0?>
                <?php $m=0?>
                <?php $e=0?>
                <?php foreach($model as $k=>$model):?>
                    <tr>

                        <td class="col1">      <a href=""><img src="http://admin.yiishop.com<?=$model['logo']?>"></a><strong><?=\yii\helpers\Html::a($model['name'])?></strong></td>
                        <td class="col3"><?=$model['shop_price']?></td>

                        <td class="col4"> <?=$model['amount']?></td>
                        <?php $m+=$model['amount']?>
                        <td class="col5"><span><?=$a=$model['shop_price']*$model['amount']?></span></td>
                      <?php $e=$a+$e?>


                    </tr>
                <?php endforeach;?>



                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=$m?>件商品，总商品金额：</span>
                                <em id="s"><?=$e?></em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em id="mom"></em>
                            </li>

                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">

        <a href="end" id="tj"></a>

        <p>应付总额：<strong id="zong"><?=$e?>元</strong></p>

    </div>

</div>
<?php
$url=yii\helpers\Url::to(['goods/shop']);
$token=Yii::$app->request->csrfToken;

$total=$e;

$this->registerJs(new yii\web\JsExpression(
    <<<JS

    var a = $('input[name="delivery"]:checked ').closest('tr').find('.jia_i').text();
$('.jia').change(function(){
         var a = $(this).closest('tr').find('.jia_i').text();
         $('#mom').text(a);
        var zhifu= $('#s').text()*1+a*1;
         $('#zong').text(zhifu);
})
$('#mom').text(a);
  var zhifu= $('#s').text()*1+a*1;
        console.log(zhifu)
         $('#zong').text(zhifu);


                $("#tj").click(function(){

                  var a = $('input[name="address_id"]:checked ').val();
                  var kid=$('input[name="delivery"]:checked ').val();
                  console.log(kid);
                   var pay=$('input[name="pay"]:checked ').val();
                //发送ajax post请求
                  console.log(a);
                var data={"_csrf-frontend":"$token","zhifu":zhifu,"a":a,"kid":kid,"pay":pay};
                console.log(data);


                $.post("$url",data);


        });


JS


));
?>



