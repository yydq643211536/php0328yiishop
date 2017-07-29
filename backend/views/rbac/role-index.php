<?php
/* @var $this yii\web\View */
?>
<h1>rbac/index</h1>
<?=\yii\bootstrap\Html::a('添加',['add-role'],['class'=>'btn btn-sm btn-primary'])?>
<table class="table table-bordered">
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['edit-role','name'=>$model->name],['class'=>'btn btn-sm btn-warning'])?>
            <?=\yii\bootstrap\Html::a('删除',['delete-role','name'=>$model->name],['class'=>'btn btn-sm btn-danger'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
