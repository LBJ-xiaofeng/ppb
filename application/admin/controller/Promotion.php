<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://#
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用TP5助手函数可实现单字母函数M D U等,也可db::name方式,可双向兼容
 * ============================================================================
 * Author: 当燃
 * 专题管理
 * Date: 2016-03-09
 */

namespace app\admin\controller;

use app\admin\model\FlashSale;
use app\admin\model\Goods;
use app\admin\model\GoodsActivity;
use app\admin\model\GroupBuy;
use app\common\model\PromGoods;
use think\AjaxPage;
use think\Page;
use app\admin\logic\GoodsLogic;
use think\Loader;
use think\Db;

class Promotion extends Base
{

    public function index()
    {
        return $this->fetch();
    }

    /**
     * 商品活动列表
     */
    public function prom_goods_list()
    {
        $where['type'] = array('eq',1);
        $count = M('prom_goods')->where($where)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $sql='select a.*,b.goods_name from tp_prom_goods as a left join tp_goods as b on a.goods_id=b.goods_id where a.type=1 order by id desc limit '.$Page->firstRow.','.$Page->listRows;
        $prom_list = M('prom_goods')->query($sql);
        foreach($prom_list as $k=>$v){
            $prom_list[$k]['expression']=$v['expression']/100;
        }
        $this->assign('prom_list', $prom_list);
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('pager', $Page);
        $this->assign('count', $count);
        return $this->fetch();
    }
    /*
     * 查看拼团商品下属团
     */
    public function get_group()
    {
        header("Content-type:text/html;charset=utf-8");
        $list_id=I('id');
        $count=M('prom_list')->where(['prom_id'=>$list_id,'status'=>1])->order('add_time desc')->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $list=M('prom_list')->where(['prom_id'=>$list_id,'status'=>1])->order('add_time desc')->limit($Page->firstRow,$Page->listRows)->select();
        foreach($list as $k=>$v){
            $list[$k]['user_id']=explode(',',$v['user_id']);
        }
        $this->assign('list', $list);
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('pager', $Page);
        $this->assign('count', $count);
        return $this->fetch();
    }
    /*
     * 查看某团下的订单
     */
    public function groupOrder()
    {
        $tid=I('tid/d');
        $order=M('order')->where(['order_prom_type'=>2,'order_prom_id'=>$tid])->order('pay_time desc')->select();
//        print_r($order);die;
        $this->assign('order',$order);
        return $this->fetch();
    }

    public function prom_goods_info()
    {
        $prom_id = I('id');
        $info['start_time'] = date('Y-m-d');
        $info['end_time'] = date('Y-m-d', time() + 3600 * 60 * 24);
        if ($prom_id > 0) {
            $info = M('prom_goods')->where("id=$prom_id")->find();
            $info['start_time'] = date('Y-m-d', $info['start_time']);
            $info['end_time'] = date('Y-m-d', $info['end_time']);
            $Goods = new Goods();
            $prom_goods = $Goods->with('SpecGoodsPrice')->where(['prom_id' => $prom_id, 'prom_type' => 3])->select();
            $this->assign('prom_goods', $prom_goods);
        }
        $this->assign('info', $info);
        $this->assign('min_date', date('Y-m-d'));
        $this->initEditor();
        return $this->fetch();
    }

    public function prom_goods_save()
    {
        if (IS_POST) {
            $data = I('post.');
            $data['type']=1;
            $data['goods_price']=$data['goods_price']*$data['expression']/100;
            $data['start_time'] = strtotime($data['start_time']);
            $data['end_time'] = strtotime($data['end_time']);
//            if($data['start_time']>time() && $data['end_time']>time()){
//                $data['is_end']=1;
//            }elseif($data['start_time']<time() && $data['end_time']>time()){
//                $data['is_end']=0;
//            }elseif(time()>$data['end_time']){
//                $data['is_end']=2;
//            }
            if (empty($data['id'])) {
                $flashSaleInsertId = M('prom_goods')->add($data);
                adminLog("管理员添加促销活动 " . $data['goods_name']);
                if ($flashSaleInsertId !== false) {
                    return $this->success('添加促销活动成功',U('Admin/promotion/prom_goods_list'));
                } else {
                    return $this->error('添加促销活动失败');
                }
            } else {
                $flashSaleInsertId = M('prom_goods')->where(['id'=>$data['id']])->save($data);
                adminLog("管理员编辑促销活动 " . $data['goods_name']);
                if ($flashSaleInsertId !== false) {
                    return $this->success('编辑促销活动成功',U('Admin/promotion/prom_goods_list'));
                } else {
                    return $this->error('编辑促销活动失败');
                }
            }
        }
        $id = I('id');
        if ($id > 0) {
            $info = M('prom_goods')->where('id='.$id)->find($id);
            $info['start_time'] = date('Y-m-d H:i', $info['start_time']);
            $info['end_time'] = date('Y-m-d H:i', $info['end_time']);
        }
        $this->assign('info', $info);
        return $this->fetch();
    }

    public function prom_goods_del()
    {
        $prom_id = I('id');
        M('prom_goods')->where("id=$prom_id")->delete();
        $this->success('删除成功', U('Promotion/prom_goods_list'));
    }


    /**
     * 活动列表
     */
    public function prom_order_list()
    {
        $parse_type = array('0' => '满额打折', '1' => '满额优惠金额', '2' => '满额送积分', '3' => '满额送优惠券');
        $level = M('user_level')->select();
        if ($level) {
            foreach ($level as $v) {
                $lv[$v['level_id']] = $v['level_name'];
            }
        }
        $count = M('prom_order')->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $prom_list = M('prom_order')->limit($Page->firstRow . ',' . $Page->listRows)->select();
//        if ($res) {  //获得适用范围（用户等级）
//            foreach ($res as $val) {
//                if (!empty($val['group']) && !empty($lv)) {
//                    $val['group'] = explode(',', $val['group']);
//                    foreach ($val['group'] as $v) {
//                        $val['group_name'] .= $lv[$v] . ',';
//                    }
//                }
//                $prom_list[] = $val;
//            }
//        }
        $this->assign('pager', $Page);// 赋值分页输出
        $this->assign('page', $show);// 赋值分页输出
        $this->assign("parse_type", $parse_type);
        $this->assign('prom_list', $prom_list);
        return $this->fetch();
    }

    public function prom_order_info()
    {
        $this->assign('min_date', date('Y-m-d'));
        $level = M('user_level')->select();
        $this->assign('level', $level);
        $prom_id = I('id');
        $info['start_time'] = date('Y-m-d');
        $info['end_time'] = date('Y-m-d', time() + 3600 * 24 * 60);
        if ($prom_id > 0) {
            $info = M('prom_order')->where("id=$prom_id")->find();
            $info['start_time'] = date('Y-m-d', $info['start_time']);
            $info['end_time'] = date('Y-m-d', $info['end_time']);
        }
        $this->assign('info', $info);
        $this->assign('min_date', date('Y-m-d'));
        $this->initEditor();
        return $this->fetch();
    }

    public function prom_order_save()
    {
        $prom_id = I('id');
        $data = I('post.');
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        $data['group'] = $data['group'] ? implode(',', $data['group']) : '';
        if ($prom_id) {
            M('prom_order')->where("id=$prom_id")->save($data);
            adminLog("管理员修改了商品促销 " . I('name'));
        } else {
            M('prom_order')->add($data);
            adminLog("管理员添加了商品促销 " . I('name'));
        }
        $this->success('编辑促销活动成功', U('Promotion/prom_order_list'));
    }

    public function prom_order_del()
    {
        $prom_id = I('id');
        $order = M('order')->where("order_prom_id = $prom_id")->find();
        if (!empty($order)) {
            $this->error("该活动有订单参与不能删除!");
        }

        M('prom_order')->where("id=$prom_id")->delete();
        $this->success('删除活动成功', U('Promotion/prom_order_list'));
    }

    public function group_buy_list()
    {
        $GroupBuy = new GroupBuy();
        $count = $GroupBuy->where('')->count();
        $Page = new Page($count, 10);
        $list = $GroupBuy->where('')->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $Page);
        return $this->fetch();
    }

    public function group_buy()
    {
        $act = I('GET.act', 'add');
        $groupbuy_id = I('get.id/d');
        $group_info = array();
        $group_info['start_time'] = date('Y-m-d');
        $group_info['end_time'] = date('Y-m-d', time() + 3600 * 365);
        if ($groupbuy_id) {
            $GroupBy = new GroupBuy();
            $group_info = $GroupBy->with('specGoodsPrice,goods')->find($groupbuy_id);
            $group_info['start_time'] = date('Y-m-d H:i', $group_info['start_time']);
            $group_info['end_time'] = date('Y-m-d H:i', $group_info['end_time']);
            $act = 'edit';
        }
        $this->assign('min_date', date('Y-m-d'));
        $this->assign('info', $group_info);
        $this->assign('act', $act);
        return $this->fetch();
    }

    public function groupbuyHandle()
    {
        $data = I('post.');
        $data['groupbuy_intro'] = htmlspecialchars(stripslashes($this->request->param('groupbuy_intro')));
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        if ($data['act'] == 'del') {

            $spec_goods = Db::name('spec_goods_price')->where(['prom_type' => 2, 'prom_id' => $data['id']])->find();
            //有活动商品规格
            if($spec_goods){
                Db::name('spec_goods_price')->where(['prom_type' => 2, 'prom_id' => $data['id']])->save(array('prom_id' => 0, 'prom_type' => 0));
                //商品下的规格是否都没有活动
                $goods_spec_num = Db::name('spec_goods_price')->where(['prom_type' => 2, 'goods_id' => $spec_goods['goods_id']])->find();
                if(empty($goods_spec_num)){
                    //商品下的规格都没有活动,把商品回复普通商品
                    Db::name('goods')->where(['goods_id' => $spec_goods['goods_id']])->save(array('prom_id' => 0, 'prom_type' => 0));
                }
            }else{
                //没有商品规格
                Db::name('goods')->where(['prom_type' => 2, 'prom_id' => $data['id']])->save(array('prom_id' => 0, 'prom_type' => 0));
            }
            $r = D('group_buy')->where(['id' => $data['id']])->delete();
            if ($r) exit(json_encode(1));
        }
        $groupBuyValidate = Loader::validate('GroupBuy');
        if($data['item_id'] > 0){
            $spec_goods_price = Db::name("spec_goods_price")->where(['item_id'=>$data['item_id']])->find();
            $data['goods_price'] = $spec_goods_price['price'];
            $data['store_count'] = $spec_goods_price['store_count'];
        }else{
            $goods = Db::name("goods")->where(['goods_id'=>$data['goods_id']])->find();
            $data['goods_price'] = $goods['shop_price'];
            $data['store_count'] = $goods['store_count'];
        }
        if(!$groupBuyValidate->batch()->check($data)){
            $return = ['status' => 0,'msg' =>'操作失败','result' => $groupBuyValidate->getError() ];
            $this->ajaxReturn($return);
        }
        $data['rebate'] = number_format($data['price'] / $data['goods_price'] * 10, 1);
        if ($data['act'] == 'add') {
            $r = Db::name('group_buy')->insertGetId($data);
            if($data['item_id'] > 0){
                //设置商品一种规格为活动
                Db::name('spec_goods_price')->where('item_id',$data['item_id'])->update(['prom_id' => $r, 'prom_type' => 2]);
                Db::name('goods')->where("goods_id", $data['goods_id'])->save(array('prom_id' => 0, 'prom_type' => 2));
            }else{
                Db::name('goods')->where("goods_id", $data['goods_id'])->save(array('prom_id' => $r, 'prom_type' => 2));
            }
        }
        if ($data['act'] == 'edit') {
            $r = Db::name('group_buy')->where(['id' => $data['id']])->update($data);
            if($data['item_id'] > 0){
                //设置商品一种规格为活动
                Db::name('spec_goods_price')->where(['prom_type' => 2, 'prom_id' => $data['id']])->update(['prom_id' => 0, 'prom_type' => 0]);
                Db::name('spec_goods_price')->where('item_id', $data['item_id'])->update(['prom_id' => $data['id'], 'prom_type' => 2]);
                M('goods')->where("goods_id", $data['goods_id'])->save(array('prom_id' => 0, 'prom_type' => 2));
            }else{
                M('goods')->where("goods_id", $data['goods_id'])->save(array('prom_id' => $data['id'], 'prom_type' => 2));
            }
        }
        if ($r !== false) {
            $this->ajaxReturn(['status' => 1,'msg' =>'操作成功','result' => '']);
        } else {
            $this->ajaxReturn(['status' => 0,'msg' =>'操作失败','result' =>'']);
        }
    }

    public function get_goods()
    {
        $prom_id = I('id/d');
        $Goods = new Goods();
        $prom_where = ['prom_id' => $prom_id, 'prom_type' => 3];
        $count = $Goods->where($prom_where)->count('goods_id');
        $Page = new Page($count, 10);
        $goodsList = $Goods->with('specGoodsPrice')->where($prom_where)->order('goods_id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $show = $Page->show();
        $this->assign('page', $show);
        $this->assign('goodsList', $goodsList);
        return $this->fetch();
    }

    public function search_goods()
    {
        $goods_id = input('goods_id');
        $intro = input('intro');
        $cat_id = input('cat_id');
        $brand_id = input('brand_id');
        $keywords = input('keywords');
        $prom_id = input('prom_id');
        $tpl = input('tpl', 'search_goods');
        $where = ['is_on_sale' => 1, 'store_count' => ['gt', 0],'is_virtual'=>0,'exchange_integral'=>0];
        $prom_type = input('prom_type/d');
        if($goods_id){
            $where['goods_id'] = ['<>',$goods_id];
        }
        if($intro){
            $where[$intro] = 1;
        }
        if($cat_id){
            $grandson_ids = getCatGrandson($cat_id);
            $where['cat_id'] = ['in',implode(',', $grandson_ids)];
        }
        if ($brand_id) {
            $where['brand_id'] = $brand_id;
        }
        if($keywords){
            $where['goods_name|keywords'] = ['like','%'.$keywords.'%'];
        }
        $Goods = new Goods();
        $count = $Goods->where($where)->where(function ($query) use ($prom_type, $prom_id) {
            if($prom_type == 3){
                //优惠促销
                if ($prom_id) {
                    $query->where(['prom_id' => $prom_id, 'prom_type' => 3])->whereor('prom_type', 0);
                } else {
                    $query->where('prom_type', 0);
                }
            }else if(in_array($prom_type,[1,2,6])){
                //抢购，团购
                $query->where('prom_type','in' ,[0,$prom_type])->where('prom_type',0);
            }else{
                $query->where('prom_type',0);
            }
        })->count();
        $Page = new Page($count, 10);
        $goodsList = $Goods->with('specGoodsPrice')->where($where)->where(function ($query) use ($prom_type, $prom_id) {
            if($prom_type == 3){
                //优惠促销
                if ($prom_id) {
                    $query->where(['prom_id' => $prom_id, 'prom_type' => 3])->whereor('prom_type', 0);
                } else {
                    $query->where('prom_type', 0);
                }
            }else if(in_array($prom_type,[1,2,6])){
                //抢购，团购
                $query->where('prom_type','in' ,[0,$prom_type]);
            }else{
                $query->where('prom_type',0);
            }
        })->order('goods_id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $GoodsLogic = new GoodsLogic;
        $brandList = $GoodsLogic->getSortBrands();
        $categoryList = $GoodsLogic->getSortCategory();
        $this->assign('brandList', $brandList);
        $this->assign('categoryList', $categoryList);
        $this->assign('page', $Page);
        $this->assign('goodsList', $goodsList);
        return $this->fetch($tpl);
    }

    //限时抢购
    public function flash_sale()
    {

        $where['type'] = array('eq',0);
        $count = M('prom_goods')->where($where)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $sql='select a.*,b.goods_name from tp_prom_goods as a left join tp_goods as b on a.goods_id=b.goods_id where a.type=0 order by id desc limit '.$Page->firstRow.','.$Page->listRows;
        $prom_list = M('prom_goods')->query($sql);
        $this->assign('prom_list', $prom_list);
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('pager', $Page);
        return $this->fetch();
    }

    public function flash_sale_info()
    {
        if (IS_POST) {
            $data = I('post.');
            $data['start_time'] = strtotime($data['start_time']);
            $data['end_time'] = strtotime($data['end_time']);
            if($data['start_time']>time() && $data['end_time']>time()){
                $data['is_end']=1;
            }elseif($data['start_time']<time() && $data['end_time']>time()){
                $data['is_end']=0;
            }elseif(time()>$data['end_time']){
                $data['is_end']=2;
            }
            if (empty($data['id'])) {
                $flashSaleInsertId = M('prom_goods')->add($data);
                adminLog("管理员添加抢购活动 " . $data['goods_name']);
                if ($flashSaleInsertId !== false) {
                    return $this->success('添加抢购活动成功',U('Admin/promotion/flash_sale'));
                } else {
                    return $this->error('添加抢购活动失败');
                }
            } else {
                $flashSaleInsertId = M('prom_goods')->where(['id'=>$data['id']])->save($data);
                adminLog("管理员编辑抢购活动 " . $data['goods_name']);
                if ($flashSaleInsertId !== false) {
                    return $this->success('编辑抢购活动成功',U('Admin/promotion/flash_sale'));
                } else {
                    return $this->error('编辑抢购活动失败');
                }
            }
        }
        $id = I('id');
        if ($id > 0) {
            $info = M('prom_goods')->where('id='.$id)->find($id);
            $info['start_time'] = date('Y-m-d H:i', $info['start_time']);
            $info['end_time'] = date('Y-m-d H:i', $info['end_time']);
        }
        $this->assign('info', $info);
        return $this->fetch();
    }

    public function flash_sale_del()
    {
        $id = I('del_id/d');
        if ($id) {
            $spec_goods = Db::name('prom_goods')->where(['id' => $id])->delete();
            exit(json_encode(1));
        } else {
            exit(json_encode(0));
        }
    }


    private function initEditor()
    {
        $this->assign("URL_upload", U('Admin/Ueditor/imageUp', array('savepath' => 'promotion')));
        $this->assign("URL_fileUp", U('Admin/Ueditor/fileUp', array('savepath' => 'promotion')));
        $this->assign("URL_scrawlUp", U('Admin/Ueditor/scrawlUp', array('savepath' => 'promotion')));
        $this->assign("URL_getRemoteImage", U('Admin/Ueditor/getRemoteImage', array('savepath' => 'promotion')));
        $this->assign("URL_imageManager", U('Admin/Ueditor/imageManager', array('savepath' => 'promotion')));
        $this->assign("URL_imageUp", U('Admin/Ueditor/imageUp', array('savepath' => 'promotion')));
        $this->assign("URL_getMovie", U('Admin/Ueditor/getMovie', array('savepath' => 'promotion')));
        $this->assign("URL_Home", "");
    }

    /**
     * 商品预售列表
     *
     */
    public function pre_sell_list()
    {
    header("Content-type: text/html; charset=utf-8");
exit("暂不支持此功能");
    }

    /**
     * 预售商品商品详情页
     */
    public function pre_sell_info()
    {
    header("Content-type: text/html; charset=utf-8");
exit("暂不支持此功能");
    }

    /**
     * 预售商品删除处理
     */
    public function pre_sell_del()
    {
        header("Content-type: text/html; charset=utf-8");
exit("暂不支持此功能");
    }

    /**
     * 预售活动成功
     */
    public function pre_sell_success()
    {
        header("Content-type: text/html; charset=utf-8");
exit("暂不支持此功能");
    }

    /**
     * 预售活动失败
     */
    public function pre_sell_fail()
    {
        header("Content-type: text/html; charset=utf-8");
exit("暂不支持此功能");
    }

}