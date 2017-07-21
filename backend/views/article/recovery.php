<?=\yii\bootstrap\Html::a('首页',['article/index'],['class'=>'btn btn-sm btn-info'])?>
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
                <td><?=$model->article_category_id?></td>
                <td><?=$model->sort?></td>
                <td><?=\backend\models\Article::getStatusOptions(false)[$model->status]?></td>
                <td><?=date('Y-m-d H:i:s',$model->create_time)?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('删除',['article/del','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?>
                    <?=\yii\bootstrap\Html::a('隐藏',['article/rec','id'=>$model->id],['class'=>'btn btn-sm btn-success'])?>
                    <?=\yii\bootstrap\Html::a('正常',['article/rec1','id'=>$model->id],['class'=>'btn btn-sm btn-info'])?>
                </td>
            </tr>


        <?php endforeach;?>
    </table>
