<?php
/* @var $this yii\web\View */
?>
<h1>管理员首页</h1>
<?=\yii\bootstrap\Html::a('添加',['admin/add'],['class'=>'btn btn-sm btn-info'])?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>注册时间</th>
        <th>修改时间</th>
        <th>最后登录时间</th>
        <th>最后登录IP</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->username?></td>
        <td><?=$model->email?></td>
        <td><?=\backend\models\Admin::$status_opt[$model->status]?></td>
        <td><?=date('Y-m-d H:i:s',$model->created_at)?></td>
        <td><?=date('Y-m-d H:i:s',$model->updated_at)?></td>
        <td><?=date('Y-m-d H:i:s',$model->last_login_time)?></td>
        <td><?=$model->last_login_ip?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['admin/edit','id'=>$model->id],['class'=>'btn btn-sm btn-warning'])?>
            <?=\yii\bootstrap\Html::a('删除',['admin/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>


<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);