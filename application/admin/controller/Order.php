<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Order extends Controller
{
    public function orderlist(){
        if(empty(session('username'))) {
            $this->error('请先登录！','adminlogin/login');
        }
        $orderlists=Db::table('orders')->where('is_pay',1)->order('id desc')->select();
        $this->assign('orderlists',$orderlists);
        return $this->fetch();
    }
    
    public function send(){
        $orderID = input('post.orderID/d');
        $order_kddh= input('post.kddh');
        $send_time = date('Y-m-d H:i:s');
        $result = Db::execute("update orders set send_time='".$send_time."',kddh='".$order_kddh."',is_status = '已发货' where id=".$orderID);
        $this->redirect('order/orderlist');
    }
    
    public function evaluatemanage(){
        $result = Db::query("select * from orders");
        $this->assign("orders",$result);
        return $this->fetch();
    }
    
    public function reply(){
        $id = $_POST['id'];
        $reply_evaluation = $_POST['reply'];
        $result = Db::execute("update orders set reply_evaluation='".$reply_evaluation."' where id=".$id);
        $data = array();
        if (!empty($result)) {
            $data['code'] = 0;
            $data['msg'] = '成功！';
            echo json_encode($data);
        } else {
            $data['code'] = -1;
            $data['msg'] = '获取失败';
            echo json_encode($data);
        }
    }
    
    public function deleteEvaluation(){
        $id = $_POST['id'];
        $result = Db::execute("update orders set evaluation='',reply_evaluation='' where id=".$id);
        $data = array();
        if (!empty($result)) {
            $data['code'] = 0;
            $data['msg'] = '成功！';
            echo json_encode($data);
        } else {
            $data['code'] = -1;
            $data['msg'] = '获取失败';
            echo json_encode($data);
        }
    }
}

