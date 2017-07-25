<?php

use yii\web\JsExpression;

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($goods,'name');
//echo $form->field($model,'sn');
echo $form->field($goods,'brand_id')->dropDownList(\backend\models\Goods::brand());
//echo $form->field($model,'goods_category_id')->dropDownList(\backend\models\Goods::goodsCategory());
echo $form->field($goods,'goods_category_id')->hiddenInput();
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';
echo $form->field($goods,'market_price');
echo $form->field($goods,'shop_price');
echo $form->field($goods,'stock');
//echo $form->field($model,'is_on_sale');
echo $form->field($goods,'status',['inline'=>true])->radioList(\backend\models\Goods::statusOption());
echo $form->field($goods,'is_on_sale',['inline'=>true])->radioList(\backend\models\Goods::asleOption());
echo $form->field($goods,'sort');
echo $form->field($goodsIntro,'content')->widget('kucha\ueditor\UEditor',[]);
echo $form->field($goods,'LOGO')->hiddenInput();

///\flyok666\uploadifive\Uploadifive::widget
//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
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
        ///将图片的路径保存到logo
        $("#goods-logo").val(data.fileUrl);
        //将上传成功的图片回显
        $("#img").attr('src',data.fileUrl);
    }
}
EOF
        ),
    ]
]);




echo \yii\bootstrap\Html::img($goods->LOGO?$goods->LOGO:false,['id'=>'img','height'=>40]);


echo \yii\bootstrap\Html::submitButton('确认',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();



/////商品分类插件
////调用视图的方法加载静态资源
///加载css
$this->registerCssFile('@web/ztree/css/zTreeStyle/zTreeStyle.css');
///加载js
$this->registerJsFile('@web/ztree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
////加载js代码
$categorys[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类','open'=>1];
$nodes = \yii\helpers\Json::encode($categorys);
//var_dump($nodes);exit;
$nodeId=$goods->goods_category_id;
//$nodeId = \backend\models\GoodsCategory::findOne(['id'=>$nodeId1->parent_id]);
//$nodeId=$categorys->parent_id;
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
		        onClick: function(event, treeId, treeNode){
		            //console.log(treeNode.id);
		            //将当期选中的分类的id，赋值给parent_id隐藏域
		            $("#goods-goods_category_id").val(treeNode.id);
		        }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};
       
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            // zTreeObj.expandAll(true);//展开全部节点
            
            
                   //获取节点
        var node = zTreeObj.getNodeByParam("id", "{$nodeId}", null);
        //选中节点
        zTreeObj.selectNode(node);
        //触发选中事件
     
JS

));