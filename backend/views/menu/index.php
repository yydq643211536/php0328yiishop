<h1>菜单列表</h1>
<?=\yii\bootstrap\Html::a('添加',['menu/add'],['class'=>'btn btn-sm btn-info'])?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>路由</th>
        <th>菜单</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->label?></td>
            <td><?=$model->url?></td>
            <td><?=$model->parent_id?></td>
            <td><?=$model->sort?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['menu/edit','id'=>$model->id],['class'=>'btn btn-sm btn-warning'])?>
                <?=\yii\bootstrap\Html::a('删除',['menu/del','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?>
            </td>
        </tr>
        <?php foreach($model->children as $child):?>
            <tr>
                <td><?=$child->id?></td>
                <td>——<?=$child->label?></td>
                <td><?=$child->url?></td>
                <td><?=$child->parent_id?></td>
                <td><?=$child->sort?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('修改',['menu/edit','id'=>$model->id],['class'=>'btn btn-sm btn-warning'])?>
                    <?=\yii\bootstrap\Html::a('删除',['menu/del','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?>
                </td>
            </tr>
        <?php endforeach;?>
    <?php endforeach;?>
</table>