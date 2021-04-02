<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Order extends Controller
{
    public function order(){
        if (empty(session('email'))) {
            $this->error('请先登录!', 'login/login');
        }
        // 查询该用户的收货地址
        $data = Db::table('address')->where('email', session('email'))->select();
        $this->assign('result', $data);
        
        $goodsData = Db::table('cart')->where('email', session('email'))->select();
        $this->assign("goods",$goodsData);
        return $this->fetch();
    }
    
    public function addAddress(){
        $responseDate = array("code" => 0, "msg" => "");
        $receiver= $_POST['receiver'];
        $address = $_POST['address'];
        $tel = $_POST['tel'];
        $post = $_POST['post'];
        $email = session('email');
        $result = Db::execute("insert into address(email, address, receiver, tel, post) value ('".$email."','".$address."','".$receiver."','".$tel."','".$post."')");
        if(empty($result)){
            $responseDate['code'] = '-1';
            $responseDate['msg'] = '失败';
            echo json_encode($responseDate);
        }else{
            $responseDate['code'] = '0';
            $responseDate['msg'] = '成功';
            echo json_encode($responseDate);
        }
    }
    
    public function changeAddress(){
        $responseDate = array("code" => 0, "msg" => "");
        $add_id = $_POST['add_id'];
        $receiver= $_POST['receiver'];
        $address = $_POST['address'];
        $tel = $_POST['tel'];
        $post = $_POST['post'];
        $result = Db::execute("update address set receiver='".$receiver."',address='".$address."',tel='".$tel."',post='".$post."' where add_id='".$add_id."'");
        //$result = Db::execute("update address set receiver='".$receiver."' where add_id='".$add_id."'");
        //$result = Db::execute("insert into address(email, address, receiver, tel, post) value ('".$email."','".$address."','".$receiver."','".$tel."','".$post."')");
        if(empty($result)){
            $responseDate['code'] = '-1';
            $responseDate['msg'] = '失败';
            echo json_encode($responseDate);
        }else{
            $responseDate['code'] = '0';
            $responseDate['msg'] = '成功';
            echo json_encode($responseDate);
        }
    }
    
    public function addOrder(){
        $data = array();
        $order = time().mt_rand();
        $email = session('email');
        $add_id = $_POST['add_id'];
        $create_time = date('Y-m-d H:i:s',time());
        $address = Db::table('address')->where('add_id',$add_id)->find();
        $goodsData = Db::table('cart')->where('email', session('email'))->select();
        foreach ($goodsData as $cart) {
            $sql = "insert into
            orders(orders_id, orders_price, goods_id, goods_count, email, add_id,create_time,img_url,goods_name,address,receiver,tel,post)
            value(
            '{$order}',
            '{$cart['max_price']}',
            '{$cart['goods_id']}',
            '{$cart['num']}',
            '{$email}',
            '{$add_id}',
            '{$create_time}',
            '{$cart['img_url']}',
            '{$cart['goods_name']}',
            '{$address['address']}',
            '{$address['receiver']}',
            '{$address['tel']}',
            '{$address['post']}'
            )";
            Db::execute($sql);
        }
        Db::execute("delete from cart where email='" .session('email'). "'");
        echo json_encode(array('code'=>'0','msg'=>'订单提交成功！','od_id'=> $order));
    }
    
}

