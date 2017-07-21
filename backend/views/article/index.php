<?=\yii\bootstrap\Html::a('添加',['article/add'],['class'=>'btn btn-sm btn-success'])?>

<?=\yii\bootstrap\Html::a('回收站',['article/recovery'],['class'=>'btn btn-sm btn-info'])?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>文章分类id</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->intro?></td>
        <td><?=\backend\models\Article::getArray()[$model->article_category_id]?></td>
        <td><?=$model->sort?></td>
        <td><?=\backend\models\Article::getStatusOptions(false)[$model->status]?></td>
        <td><?=date('Y-m-d H:i:s',$model->create_time)?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$model->id],['class'=>'btn btn-sm btn-warning'])?>
            <?=\yii\bootstrap\Html::a('删除',['article/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?>
            <?=\yii\bootstrap\Html::a('详情',['article/details','id'=>$model->id],['class'=>'btn btn-sm btn-primary'])?>
        </td>
    </tr>
        
        
    <?php endforeach;?>
</table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);