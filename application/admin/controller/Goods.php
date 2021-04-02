<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Goods as GoodsModel;
use think\Request;
use think\Db;
class Goods extends Controller
{
    public function index(){
        if(empty(session('username')))
        {
            $this->error('请先登录','adminlogin/index');
        }
        $goods=GoodsModel::all();
        $this->assign('digitals',$goods);
        return $this->fetch();
    }
    
    public function digitaladd(){
        return $this->fetch();
    }
    
    public function addDigital(Request $request){
        $goods_id=$request->param('goods_id');
        if(empty($goods_id)){
            $this->error('请填写产品编号');
        }
        $good_id=GoodsModel::get($goods_id);
        if(!empty($good_id)){
            $this->error('您填写产品编号已存在！');
        }
        $goods=new GoodsModel();
        $goods->goods_id=$goods_id;
        $goods->goods_name=$request->param('goods_name');
        $goods->description=$request->param('description');
        $goods->cate_id=$request->param('cate_id');
        $cate_id = $request->param('cate_id');
        $result = Db::table('cate')->where('cate_id',$cate_id)->find();
        $goods->cate_name=$result['cate_name'];
        $goods->brand=$request->param('brand');
        $goods->max_price=$request->param('max_price');
        $goods->stock=$request->param('stock');
        
        $img_url=$request->file('img_url');
        if	(empty($img_url)){
            $this->error('请上传商品图片');
        }
        $info=$img_url->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'assets/img'.DS.'goods_img','');
        $goods->img_url=$info->getSaveName();
        $goods->save();
        $this->success('添加成功！','goods/index');
    }
    
    public function digitalDelete(){
        $goods=GoodsModel::get(input('get.goods_id'));
        $goods->delete();
        $this->redirect('goods/index');
    }
    
    public function digitalupdate(){
        $goods=GoodsModel::get(input('get.goods_id'));
        $this->assign('digital',$goods);
        return $this->fetch();
    }
    
    public function updateDigital(Request $request){
        
        $goods=GoodsModel::get(input('post.goods_id'));
        $goods->goods_name=$request->param('goods_name');
        $goods->description=$request->param('description');
        $goods->cate_id=$request->param('cate_id');
        $cate_id = $request->param('cate_id');
        $result = Db::table('cate')->where('cate_id',$cate_id)->find();
        $goods->cate_name=$result['cate_name'];
        $goods->brand=$request->param('brand');
        $goods->max_price=$request->param('max_price');
        $goods->stock=$request->param('stock');
        
        $img_url=$request->file('img_url');
        if(!empty($img_url)){
            $info=$img_url->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'assets/img'.DS.'goods_img','');
            $goods->img_url=$info->getSaveName();
        }
        $goods->save();
        $this->success('修改成功！','goods/index');
    }
}

