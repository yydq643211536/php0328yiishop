
<table>
    <?php foreach($pics as $pic):?>
    <tr><?=\yii\bootstrap\Html::img($pic->path,['width'=>800],['class'=>'auto'])?></tr>
    <?php endforeach;?>
</table>
<?=\yii\bootstrap\Html::a('返回',['goods/index'],['class'=>'btn btn-primary'])?>