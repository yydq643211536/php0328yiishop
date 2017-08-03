<?php
namespace frontend\assets;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class ListAsset extends AssetBundle{
    public $basePath = '@webroot';//静态资源的硬盘路径
    public $baseUrl = '@web';//静态资源的url路径
    //需要加载的css文件
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/home.css',
        'style/goods.css',
        'style/list.css',
        'style/common.css',
        'style/address.css',


        'style/footer.css',
        'style/bottomnav.css',
        'style/jqzoom.css',

    ];
    //需要加载的js文件
    public $js = [
//        'js/jquery-1.8.3.min.js',
        'js/header.js',
      'js/home.js',
        'js/jqzoom-core.js',
        'js/goods.js',
        'js/index.js',

    ];
    //和其他静态资源管理器的依赖关系
    public $depends = [
     JqueryAsset::class
    ];

}
