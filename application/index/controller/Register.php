<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Register extends Controller{
    
    public function register(){
        return view();
    }
    
    public function doRegister(){
        $param = input('post.');   //使用input助手
        if(empty($param['email'])) {
            $this->error('email不能为空');
        }
        
        //$code = $param['code'];
        //$captcha=new \think\captcha\Captcha();
        //if(!$captcha->check($code))	{
        //    $this->error('验证码错误');
        //}
        
        //查找数据库，看email地址是否被注册
        $data = db('users')->where('email', $param['email'])->find();  //使用了db助手
        //$data = Db::table('members')->where('email', $param['email'])->find();
        if(!empty($data)) {
            $this->error('该用户已被注册！');
        }
        
        
        //将注册信息添加到数据库中
        $result = Db::execute("insert into users(email,password) values('" . $param['email'] . "','" .md5($param['passw1']) . "')");
        
        
        //注册成功，跳转到登录界面
        $this->redirect(url('login/login'));
    }
    
}
?>