<?php
namespace app\index\controller;
use think\Db;
use think\Controller;

class Personal extends Controller
{
    public function personal(){
        if (empty(session('email'))) {
            $this->error('请先登录!', 'login/login');
        }
        $data = Db::table('cart')->where('email', session('email'))->select();
        $this->assign('result', $data);
        return $this->fetch();
    }
    
    public function getOrder(){
        $data = array();
        $result = Db::table('orders')->where("email",session('email'))->select();
        if (empty($result)) {
            $data['code'] = -1;
            $data['msg'] = '获取失败';
            echo json_encode($data);
            
        } else {
            $data['code'] = 0;
            $data['msg'] = '成功！';
            $data['data'] = $result;
            echo json_encode($data);
        }
    }
    
    public function getAddress(){
        $data = array();
        $result = Db::table('address')->where("email",session('email'))->select();
        if (empty($result)) {
            $data['code'] = -1;
            $data['msg'] = '获取失败';
            echo json_encode($data);
            
        } else {
            $data['code'] = 0;
            $data['msg'] = '成功！';
            $data['data'] = $result;
            echo json_encode($data);
        }
    }
    
    public function getOnlyAddress(){
        $data = array();
        $add_id = $_POST['add_id'];
        $result = Db::table('address')->where("add_id",$add_id)->find();
        if (empty($result)) {
            $data['code'] = -1;
            $data['msg'] = '获取失败';
            echo json_encode($data);
        } else {
            $data['code'] = 0;
            $data['msg'] = '成功！';
            $data['data'] = $result;
            echo json_encode($data);
        }
    }
    
    public function changeStatus(){
        $id=$_POST['id'];
        $status = $_POST['status'];
        $data = array();
        if($status==1){
            $result=Db::execute("update orders set is_status='已收货'  where id=".$id);
        }else if($status==2){
            $evaluate_time = date('Y-m-d H:i:s',time());
            $result=Db::execute("update orders set is_status='已评价',evaluation='五星好评',evaluate_time='".$evaluate_time."'  where id=".$id);
        }
        if (!empty($result)) {
            $data['code'] = 0;
            $data['msg'] = '成功！';
            $data['data'] = $result;
            $data['id'] = $id;
            echo json_encode($data);
        } else {
            $data['code'] = -1;
            $data['msg'] = '获取失败';
            echo json_encode($data);
        }
    }
    
    public function deleteAddress(){
        $add_id=$_POST['add_id'];
        $data = array();
        $result=Db::execute("delete from address where add_id=".$add_id);
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
    
    public function accountsecurity(){
        return view();
    }
    
    public function changePassword(){
        $passPre = $_POST['newPass'];
        $password = md5($passPre);
        
        $passold = $_POST['OldPass'];
        $oldPass = md5($passold);
        $data = array();
        $result1 = Db::table('users')->where("email",session('email'))->find();
        if(!empty($result1)){
            $sql = "UPDATE users SET password='".$password."' WHERE email = '".session('email')."' AND password='".$oldPass."'";
            $result2 = Db::execute($sql);
            if(!empty($result2)){
                $data['status'] = 'ok';
                echo json_encode($data);
            }
            exit();
        }else{
            $data['status'] = 'err';
            echo json_encode($data);
            exit();
        }
    }
}

