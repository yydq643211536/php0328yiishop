<h1>商品添加</h1>
<ul class="breadcrumb">
    <li><a href="index">首页</a></li>
    <li><a href="index">商品列表</a></li>
    <li class="active">商品添加</li>
</ul>
<?php
use yii\web\JsExpression;
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \yii\bootstrap\Html::img(false,['id'=>'img','height'=>50]);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 120,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        $("#goods-logo").val(data.fileUrl);
        $("#img").attr('src',data.fileUrl);
    }
}
EOF
        ),
    ]
]);
//echo $form->field($model,'goods_category_id')->dropDownList(\app\models\Goods::getGoodsCategorys());
echo $form->field($model,'goods_category_id')->hiddenInput();
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';
echo $form->field($model,'brand_id')->dropDownList(\app\models\Goods::getBrands());
echo $form->field($model,'market_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'stock');
echo $form->field($model,'is_on_sale',['inline'=>1])->radioList(\app\models\Goods::$sale_opt);
echo $form->field($model,'sort')->textInput(['type'=>'number']);
echo $form->field($goodsintro,'content')->widget('kucha\ueditor\UEditor',[]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();
//加载静态资源css js
$this->registerCssFile('/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
/*$nodes= '[
            {id:1, pId:0, name: "父节点1"},
            {id:11, pId:1, name: "子节点1"},
            {id:12, pId:1, name: "子节点2"}
        ]';*/
//增加顶级分类
$categorys[]=['id'=>0,'parent_id'=>0,'name'=>'顶级分类','open'=>1];//展开一级分类
$nodes=\yii\helpers\Json::encode($categorys);
$nodesid=$model->goods_category_id;
//加载js内容
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback: {
                    onClick: function(event, treeId, treeNode) {
                       // console.debug(treeNode.id);
                       $('#goods-goods_category_id').val(treeNode.id)
                    }
                }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};
        $(document).ready(function(){
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            var node = zTreeObj.getNodeByParam("id",$nodesid, null);
            //获取选中的基点
            zTreeObj.selectNode(node);
        });
JS
));