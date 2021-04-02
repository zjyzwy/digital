<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    public function index()
    {
        $brand = Db::table('goods')->distinct('true')->field('brand')->select();
        $this->assign('brands',$brand);
        
        $goods_name=input('post.goods_name');
        $brands=input('post.brand');
        $minprice = input('post.minprice');
        $maxprice = input('post.maxprice');
        if(empty($maxprice)){
            $maxprice=100000;
        }
        if(empty($minprice)){
            $minprice=0;
        }
        
        $searchstr = 'max_price between '. $minprice.' and '.$maxprice;
        if(!empty($brands)){
            $searchstr.=" and brand='".$brands ."'";
        }
        if(!empty($goods_name)){
            $searchstr.=" and goods_name like '%" . $goods_name . "%'";
        }
        $result=Db::table('goods')->where($searchstr)->order('goods_id desc')->select();
        $this->assign('results',$result);
        return $this->fetch();
    }
    
    public function showproducts(){
        
        $param = input('post.');
        $cate_list = Db::query("SELECT * FROM cate");
        $this->assign('cate_lists',$cate_list);
        if(!empty($param)){
            $cate_id = $param['cate-radio'];
            $this->assign("cate_id",$cate_id);
            $data = Db::name('goods')
                ->where("cate_id",$cate_id)
                ->order('goods_id')
                //->paginate(9,false,['query'=>request()->param()]);
                ->paginate(9,false,
                    ['type' => 'Bootstrap',
                        'var_page' => 'page',
                        'path'=>'javascript:AjaxPage([PAGE]);',
                        'query' =>request()->param(),
                    ]);
            $this->assign('result',$data);
        }else{
            $data = Db::table('goods')->order('goods_id')->paginate(9);
            $this->assign('result',$data);
        }
        return $this->fetch();
    }
    
    public function filterproducts(){
        //$param = input('post.');
        $cate_id = $_REQUEST['cate-radio'];
        $data = Db::table('goods')
                        ->where("cate_id",$cate_id)
                        ->order('goods_id')
                        ->paginate(9);
        $this->assign('result',$data);
        $page=$data->render();
        $this->assign('page',$page);
        return $this->fetch();
        
    }
    
}
