<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Cart extends Controller
{
    public function cart(){
        if (empty(session('email'))) {
            $this->error('请先登录!', 'login/login');
        }
        $data = Db::table('cart')->where('email', session('email'))->select();
        $this->assign('result', $data);
        return $this->fetch();
    }
    
    public function addCart()
    {
        //判断是否登录（获取登录的session信息，并判断是否为empty）
        $param = input('get.');
        if (empty(session('email'))) {
            $this->error('请先登录!', 'login/login');
        }
        
        //判断是否选择商品
        if(empty('get.goods_id')){
            $this->error('请选择商品!', 'index/index');
        }
        //判断购物车是否存放了该用户所选的商品，如果不存在，则将该商品放入购物车,如果存在，则把原来数量加1
        $data = Db::table('cart')->where('email', session('email'))
        ->where('goods_id', $param['goods_id'])
        ->find();
        if (empty($data)) {
            $detail = Db::table('goods')
            ->where('goods_id',$param['goods_id'])
            ->find();
            $result = Db::execute("insert into cart(cartID,email,goods_id,num,img_url,goods_name, max_price, description) values(null,'" . session('email') . "','" . $detail['goods_id'] . "',1,'" . $detail['img_url'] ."','" .$detail['goods_name']."','" .$detail['max_price']."','" .$detail['description']."')");
        } else {
            $result = Db::execute("update cart set num=num+1 where email='" . session('email') . "' and goods_id='" . $param['goods_id'] . "'");
        }
        // 跳转
        $this->redirect(url('cart/cart'));
        
    }
    
    public function clearCart(){
        $result=Db::execute("delete from cart where email='" .session('email'). "'");
        //$this->redirect(url('index/index'));
    }
    
    public function deleteCart(){
        $param = input('get.');
        $result=Db::execute("delete from cart where cartID=".$param['cartID']);
        //$this->redirect(url('cart/cart'));
    }
    
    public function updateCart(){
        $param = input('get.');
        $result=Db::execute("update cart set num=".$param['num']." where cartID=".$param['cartID']);
        //$this->redirect(url('cart/cart'));
    }
}

