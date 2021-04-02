<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Digitaldetail extends Controller
{
    public function digitaldetail(){
        $param = $_GET['gd_id'];
        if(!empty($param)){
            
        }
        $data = Db::table("goods")->where("goods_id",$param)->find();
        $this->assign("digital",$data);
        return $this->fetch();
            
    }
}

