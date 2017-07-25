<h1>商品列表</h1>

<?=\yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-primary'])?>
<?=\yii\bootstrap\Html::a('回收站',['goods/trash'],['class'=>'btn btn-warning'])?>
<table class="table">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>货号</th>
        <th>LOGO</th>
        <th>商品分类</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>浏览次数</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goods as $good):?>
        <tr>
        <td><?=$good->id?></td>
        <td><?=$good->name?></td>
        <td><?=$good->sn?></td>
        <td><?=\yii\bootstrap\Html::img($good->LOGO,['height'=>40])?></td>
        <td><?=\backend\models\Goods::goodsCategory()[$good->goods_category_id]?></td>
        <td><?=\backend\models\Goods::brand()[$good->brand_id]?></td>
        <td><?=$good->market_price?></td>
        <td><?=$good->shop_price?></td>
        <td><?=$good->stock?></td>
        <td><?=\backend\models\Goods::asleOption()[$good->is_on_sale]?></td>
        <td><?=\backend\models\Goods::statusOption()[$good->status]?></td>
        <td><?=$good->sort?></td>
        <td><?=date('Y-m-d',$good->create_time)?></td>
        <td><?=$good->view_times?></td>
        <td><?=\yii\bootstrap\Html::a('预览',['goods/pindex','goods_id'=>$good->id],['class'=>'glyphicon glyphicon-off'])?>
            <?=\yii\bootstrap\Html::a('添加图片',['goods/pic','goods_id'=>$good->id],['class'=>'glyphicon glyphicon-camera'])?>
            <?=\yii\bootstrap\Html::a('修改',['goods/eidt','id'=>$good->id],['class'=>'btn btn-info'])?>
            <?=\yii\bootstrap\Html::a('删除',['goods/del','id'=>$good->id],['class'=>'btn btn-warning'])?></td>
        </tr>
    <?php endforeach;?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);
