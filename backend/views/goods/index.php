<h1>商品列表</h1>
<P>
    <?=\yii\bootstrap\Html::a('添加商品',['goods/add'],['class'=>'btn  btn-success glyphicon glyphicon-plus'])?>
</P>
<div class="row">
    <?php $form=\yii\bootstrap\ActiveForm::begin(['options' =>['class' => 'navbar-form navbar-left'],]);?>
    <?= $form->field($model,'keyword')->textInput(['placeholder'=>'标题'])->label('');?>
    <?= $form->field($model,'goods_sn')->textInput(['placeholder'=>'货号'])->label('');?>
    <?= $form->field($model,'price_small')->textInput(['placeholder'=>'￥'])->label('');?>
    <?= $form->field($model,'price_big')->textInput(['placeholder'=>'￥'])->label('&nbsp;——');?>
    <?= $form->field($model,'goods_sale')->radioList(['1'=>'在售','0'=>'下线'],['class'=>'radio-inline'])->label('')?>
    <div class="form-group" style="position: relative;top: -6px;left: 10px;">
        <?= \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-primary glyphicon glyphicon-search']);?>
    </div>
    <?php \yii\bootstrap\ActiveForm::end(); ?>
</div>
<p></p>
<table class="table table-bordered table-condensed table-hover">
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>货号</td>
        <td>分类</td>
        <td>商品品牌</td>
        <td>市场价格</td>
        <td>商城价格</td>
        <td>库存</td>
        <td>是否上架</td>
        <td>状态</td>
        <td>排序</td>
        <td>添加时间</td>
        <td>浏览次数</td>
        <td>LOGO</td>
        <td>操作</td>
    </tr>
    <?php foreach ($goods as $good): ?>
        <tr>
            <td><?=$good->id?></td>
            <td><?=$good->name?></td>
            <td><?=$good->sn?></td>
            <td><?=$good->name?></td>
            <td><?=$good->brand->name?></td>
            <td><?=$good->market_price?></td>
            <td><?=$good->shop_price?></td>
            <td><?=$good->stock?></td>
            <td><?=\backend\models\Goods::$sale_opt[$good->is_on_sale]?></td>
            <td><?=\backend\models\Goods::$status_opt[$good->status]?></td>
            <td><?=$good->sort?></td>
            <td><?=date('Y-m-d',$good->create_time)?></td>
            <td><?=$good->view_times?></td>
            <td><?=\yii\bootstrap\Html::img($good->logo,['height'=>50])?></td>
            <td>
                <?=\yii\bootstrap\Html::a('添加',['goods/gallery','id'=>$good->id],['class'=>'btn btn-success btn-sm'])?>
                <?=\yii\bootstrap\Html::a('修改',['goods/edit','id'=>$good->id],['class'=>'btn btn-warning btn-sm'])?>
                <?=\yii\bootstrap\Html::a('删除',['goods/del','id'=>$good->id],['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);