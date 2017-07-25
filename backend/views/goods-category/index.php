<?=\yii\bootstrap\Html::a('添加',['goods-category/add2'],['class'=>'btn btn-sm btn-success'])?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>父类id</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=str_repeat('&emsp;&emsp;',$model->depth).$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->parent_id?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-sm btn-warning'])?>
                <?=\yii\bootstrap\Html::a('删除',['goods-category/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>