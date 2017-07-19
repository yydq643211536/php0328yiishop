<?=\yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-sm btn-primary'])?>
<?=\yii\bootstrap\Html::a('回收站',['brand/recovery'],['class'=>'btn btn-sm btn-success'])?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>LOGO</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->intro?></td>
        <td><?=\yii\bootstrap\Html::img($model->logo?$model->logo:'/upload/20170718/596de75a631df.jpg',['height'=>50])?></td>
        <td><?=$model->sort?></td>
        <td><?=\backend\models\Brand::getStatusOptions(false)[$model->status]?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$model->id],['class'=>'btn btn-sm btn-warning'])?>
            <?=\yii\bootstrap\Html::a('删除',['brand/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?>
        </td>
    </tr>
<!--        warning success info danger primary-->
    <?php endforeach;?>
</table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);