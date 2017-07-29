<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
//    $menuItems = [
//        ['label' => '分类', 'items'=>[
//            ['label' => '分类列表', 'url' => ['goods-category/index']],
//            ['label' => '添加分类', 'url' => ['goods-category/add']],
//        ]],
//        ['label' => '品牌', 'items'=>[
//            ['label' => '品牌列表', 'url' => ['brand/index']],
//            ['label' => '添加品牌', 'url' => ['brand/add']],
//        ]],
//        ['label' => '商品', 'items'=>[
//        ['label' => '商品列表', 'url' => ['goods/index']],
//        ['label' => '添加商品', 'url' => ['goods/add']],
//        ]],
//        ['label' => '注册', 'items'=>[
//        ['label' => '注册列表', 'url' => ['admin/index']],
//        ['label' => '注册添加', 'url' => ['admin/add']],
//        ]],
//        ['label' => '修改密码', 'url' => ['admin/ch-pw']],
//    ];
    $menuItems = [];
    $menus = \backend\models\Menu::findAll(['parent_id'=>0]);
    foreach ($menus as $menu){
        //一级菜单
        $items = [];
        foreach ($menu->children as $child){
            //判断当前用户是否有改该路由（菜单）的权限
            if(Yii::$app->user->can($child->url)){
                $items[] = ['label'=>$child->label,'url'=>[$child->url]];

            }
//            var_dump($child);exit;
        }
        //没有子菜单时，不显示一级菜单
        if(!empty($items)){
            $menuItems[] = ['label'=>$menu->label,'items'=>$items];
        }

    }
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '登录', 'url' => ['admin/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                '注销  (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
