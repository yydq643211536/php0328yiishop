<?php
namespace frontend\controllers;


use frontend\comporents\SphinxClient;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\GoodsCategory;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\HttpException;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

class GoodsController extends Controller
{

    //x
    public $enableCsrfValidation = false;
    public $layout = false;

    public function actionIndex($goods_id)
    {
        $id = \Yii::$app->request->get('goods_id', '');
        if (!$goods_id) {
            $models = Goods::find()->limit(7)->all();
        } else {
            $models = Goods::findAll(['goods_category_id' => $goods_id]);
        }
        return $this->render('list', ['models' => $models]);
    }

    public function actionContent($id)
    {
        $model = Goods::findOne(['id' => $id]);

        return $this->render('goods', ['model' => $model]);
    }

    public function actionAdd()
    {
        $this->layout = 'login';
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id' => $goods_id]);
        //判断是否有商品
        if ($goods == null) {
            throw new NotFoundHttpException('商品不存在');
        }
        //判断是否登陆
        if (\Yii::$app->user->isGuest) {
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');

            if ($cookie == null) {
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);
            }
            //response只读
            $cookies = \Yii::$app->response->cookies;
            //判断是否有商品，有就商品累加
            if (key_exists($goods->id, $cart)) {
                $cart[$goods_id] += $amount;
            } else {
                $cart[$goods_id] = $amount;
            }
            //保存cookie
            $cookie = new Cookie([
                'name' => 'cart', 'value' => serialize($cart)
            ]);
            $cookies->add($cookie);
        } else {
            $member = \Yii::$app->user->id;
            $mode = new Cart();
            if ($model = Cart::findOne(['goods_id' => $goods_id])) {
                $model->amount += $amount;
                $model->save(false);
            } else {
                $mode->amount = $amount;
                $mode->goods_id = $goods_id;
                $mode->member_id = $member;
                $mode->save(false);
            }
        }
        return $this->redirect(['goods/cart']);

    }
    //购物车的数据
    public function actionCart(){
        $this->layout='login';
        if(\Yii::$app->user->isGuest){
            $cookies=\Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');
            if($cookie==null){
                $cart=[];
            }else{
                $cart=unserialize($cookie->value);
            }
            $model=[];
            foreach($cart as $goods_id=>$amount){
                $goods= Goods::findOne(['id'=>$goods_id])->attributes;
                $goods['amount']=$amount;
                $model[]=$goods;
            }
        }else{
            $cookies=\Yii::$app->request->cookies;
            $member=\Yii::$app->user->id;
            $cookie=$cookies->get('cart');
            if($cookie==null){
                $cart=[];
            }else{
                $cart=unserialize($cookie->value);
            }
            $mode=new Cart();

            foreach($cart as $goods_id=>$amount) {
                if ($model = Cart::findOne(['goods_id' => $goods_id])) {
                    $model->amount += $amount;
                    $model->save();
                } else {
                    $mode->amount = $amount;
                    $mode->goods_id = $goods_id;
                    $mode->member_id = $member;
                    $mode->save();
                }
            }
            $cooki=\Yii::$app->response->cookies;
            $cookie=$cookies->get('cart');
            $cooki->remove('cart');

            $model=[];
            $mo=Cart::find()->where(['member_id'=>$member])->all();
            foreach($mo as $goods_id ){
                $goods= Goods::findOne(['id'=>$goods_id['goods_id']])->attributes;
                $goods['amount']=$goods_id['amount'];
                $model[]=$goods;
            }


        }



        return $this->render('cart',['model'=>$model]);

    }

    //更新购物车的数据
    public function actionUpdate(){

        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        if(\Yii::$app->user->isGuest){
            //未登录
            //先获取cookie中的购物车数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie == null){
                //cookie中没有购物车数据
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
                //$cart = [2=>10];
            }
            //将商品id和数量存到cookie   id=2 amount=10  id=1 amount=3
            $cookies = \Yii::$app->response->cookies;
            /*$cart=[
                ['id'=>2,'amount'=>10],['id'=>1,'amount'=>3]
            ];*/
            //检查购物车中是否有该商品,有，数量累加
            /*if(key_exists($goods->id,$cart)){
                $cart[$goods_id] += $amount;
            }else{
                $cart[$goods_id] = $amount;
            }*/
            if($amount){
                $cart[$goods_id] = $amount;
            }else{
                if(key_exists($goods['id'],$cart)) unset($cart[$goods_id]);
            }
//            $cart = [$goods_id=>$amount];
            $cookie = new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{//已登录  修改数据库里面的购物车数据
            $member=\Yii::$app->user->id;
            $goods_id = \Yii::$app->request->post('goods_id');
            $amount = \Yii::$app->request->post('amount');
            $goods = Goods::findOne(['id'=>$goods_id]);
            if($goods==null){
                throw new NotFoundHttpException('商品不存在');
            }
            $model=Cart::findOne(['goods_id'=>$goods_id]);
            if($amount==0){
                Cart::findOne(['goods_id'=>$goods_id])->delete();
            }
            $model->amount=$amount;
            $model->save();




        }

    }

    //订单
    public function actionOrder(){
        $this->layout='login';
        //判断用户是否登陆没有登陆请用户先登录
        if(  \Yii::$app->user->isGuest){
            \Yii::$app->session->setFlash('danger','请先登录');
            return $this->redirect(['member/login']);
        }
        //如果用户登录获取用户id
        $member_id=\Yii::$app->user->id;

        //查询用户的地址
        $address=Address::find()->where(['user_id'=>$member_id])->all();
        //快递方式
        $delivery= [
            ['id'=>'1', 'kd'=>'普通快递送货上门','jq'=>'10.00','xq'=>'每张订单不满499.00元,运费15.00元, 订单4...'],
            ['id'=>'2','kd'=>'特快专递','jq'=>'40.00','xq'=>'每张订单不满499.00元,运费40.00元, 订单4...'],
            ['id'=>'3','kd'=>'加急快递送货上门','jq'=>'10.00','xq'=>'每张订单不满499.00元,运费15.00元, 订单4...'],
            ['id'=>'4','kd'=>'平邮','jq'=>'10.00','xq'=>'每张订单不满499.00元,运费15.00元, 订单4...'],
        ];

        $pay= [
            ['id'=>'1', 'kd'=>'货到付款','xq'=>'送货上门后再收款，支持现金、POS机刷卡、支票支付'],
            ['id'=>'2', 'kd'=>'在线支付','xq'=>'即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
            ['id'=>'3', 'kd'=>'上门自提','xq'=>'自提时付款，支持现金、POS刷卡、支票支付'],
            ['id'=>'4', 'kd'=>'邮局汇款','xq'=>'通过快钱平台收款'],
        ];
        $mode=Cart::find()->where(['member_id'=>$member_id])->all();
        $model=[];
        foreach($mode as $goods_id ){
            $goods= Goods::findOne(['id'=>$goods_id['goods_id']])->attributes;
            $goods['amount']=$goods_id['amount'];
            $model[]=$goods;
        }




        return $this->render('order',['address'=>$address,'delivery'=>$delivery,'pay'=>$pay,'model'=>$model]);
    }

    //点击结算后
    public function actionShop()
    {
        $model = \Yii::$app->request->post();
//        var_dump($model);exit;
        $order = new Order();
        $delivery = [
            ['id' => '1', 'kd' => '普通快递送货上门', 'jq' => '10.00', 'xq' => '每张订单不满499.00元,运费15.00元, 订单4...'],
            ['id' => '2', 'kd' => '特快专递', 'jq' => '40.00', 'xq' => '每张订单不满499.00元,运费40.00元, 订单4...'],
            ['id' => '3', 'kd' => '加急快递送货上门', 'jq' => '10.00', 'xq' => '每张订单不满499.00元,运费15.00元, 订单4...'],
            ['id' => '4', 'kd' => '平邮', 'jq' => '10.00', 'xq' => '每张订单不满499.00元,运费15.00元, 订单4...'],
        ];

        $pay = [
            ['id' => '1', 'kd' => '货到付款', 'xq' => '送货上门后再收款，支持现金、POS机刷卡、支票支付'],
            ['id' => '2', 'kd' => '在线支付', 'xq' => '即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
            ['id' => '3', 'kd' => '上门自提', 'xq' => '自提时付款，支持现金、POS刷卡、支票支付'],
            ['id' => '4', 'kd' => '邮局汇款', 'xq' => '通过快钱平台收款'],
        ];
        $address = Address::findOne(['id' => $model['a']]);
        $kid = $model['kid'];
        $deli = $delivery[$kid - 1]['xq'];

        $delivery_price = $delivery[$kid - 1]['jq'];

        $pa = $model['pay'];
        $pay_name = $pay[$pa - 1]['xq'];
        $order->member_id = \Yii::$app->user->id;
        $order->name = $address->name;
        $order->province = $address->province;
        $order->city = $address->city;
        $order->area = $address->area;
        $order->address = $address->detail;

        $order->tel = $address->tel;

        $order->delivery_id = $kid;
        $order->delivery_name = $deli;
        $order->delivery_price = $delivery_price;
        $order->payment_id = $pa;
        $order->payment_name = $pay_name;
        $order->total = $model['zhifu'];
        $order->trade_no = $b = mt_rand(100000, 999999);
        $order->status	 = 1;
        $order->create_time = time();
        $trans=\Yii::$app->db->beginTransaction();
//        exit;
        try{
            if(! $order->save(false)){
                throw new Exception('保存失败');
            }


//        $order_goods->order_id=$order->id;
            $ido = \Yii::$app->user->id;

            $carts = Cart::find()->where(['member_id' => $ido])->all();
            foreach ($carts as $cart) {
                //实例化

                $goodsInfo = Goods::findOne(['id' => $cart->goods_id]);
                $order_goods = new OrderGoods();
                $order_goods->order_id = $order->id;
                $order_goods->goods_id = $goodsInfo->id;
                $order_goods->goods_name = $goodsInfo->name;
                $order_goods->logo = $goodsInfo->logo;
                $order_goods->price = $goodsInfo->shop_price;
                $order_goods->amount = $cart->amount;
                $order_goods->total = $goodsInfo->shop_price * $cart->amount;

                $goodsInfo->stock -= $cart->amount;

                if($goodsInfo->stock < 0){
                    throw new HttpException('库存不足');
                }

                if(!$goodsInfo->save() || !$order_goods->save()){
                    throw new HttpException('保存失败！');
                }
            }
            if(!Cart::deleteAll(['member_id'=>\Yii::$app->user->id])){
                throw new HttpException('删除失败！');
            }

            $trans->commit();
        }catch (HttpException $e){
            $trans->rollBack();
        }






        Cart::deleteAll(['member_id'=>\Yii::$app->user->id]);


    }
    public function actionEnd(){
        $this->layout='login';
        return $this->render('end');

    }

    public function actionIndex1(){
        $this->layout = 'index';
        return $this->render('index');
    }
    //分词搜索
    public function actionSou(){
        $info=\Yii::$app->request->get($name='ke');

        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
        $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode ( SPH_MATCH_ALL);
        $cl->SetLimits(0, 1000);
//        $info = '1';
        $res = $cl->Query($info, 'goods');
        if($res==false){
            throw new NotFoundHttpException('没有找到');
        }
        $ids=ArrayHelper::map($res['matches'],'id','id');

        $model=Goods::findAll(['id'=>$ids]);
//        var_dump($model);
//        exit;
        return $this->render('list',['model'=>$model]);

    }
}