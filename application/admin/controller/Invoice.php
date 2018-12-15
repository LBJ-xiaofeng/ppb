<?php

/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://#
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ============================================================================
 * 发票控制器
 * Author: 545
 * Date: 2017-10-23
 */

namespace app\admin\controller;

use think\AjaxPage;
use think\Db;
use think\Page;

class Invoice extends Base {
    /*
     * 初始化操作
     */

    public function _initialize() {
        
        parent::_initialize();
        C('TOKEN_ON', false); // 关闭表单令牌验证
    }

    /*
     * 发票列表
     */

    public function index() {
       header("Content-type: text/html; charset=utf-8");
        $model=M('invoice');
        if(IS_POST){
            $where['atime']=array('<',strtotime(I('post.start_time')));
            $where['atime']=array('>',strtotime(I('post.end_time')));
            $where['status']=array('eq',I('post.status'));
            $count=$model->where($where)->count();
            $Page  = new AjaxPage($count,10);
            $show = $Page->show();
            $data=$model->where($where)->order('atime desc')->limit("{$Page->firstRow},{$Page->listRows}")->select();
            $start_time=date('Y-m-d 0:0:0',strtotime('-7 day'));
            $end_time=date('Y-m-d 0:0:0');
        }else{
            $count = $model->count();
            $Page  = new AjaxPage($count,10);
            $show = $Page->show();
            $data=$model->where(['status'=>0])->order('atime desc')->limit("{$Page->firstRow},{$Page->listRows}")->select();
            $start_time=date('Y-m-d 0:0:0',strtotime('-7 day'));
            $end_time=date('Y-m-d 0:0:0');
        }
        $this->assign('show',$show);
        $this->assign('page',$Page);
        $this->assign('count',$count);
        $this->assign('start_time',$start_time);
        $this->assign('end_time',$end_time);
        $this->assign('data',$data);
        return $this->fetch();
    }
    /**
     * 发票审核、编辑
     */
    public function detail() {
    header("Content-type: text/html; charset=utf-8");
        if(IS_POST){
            $oid=I('post.order_id');
            $data=I('post.');
            $data['ctime']=time();
            $res=M('invoice')->where(['order_id'=>$oid])->save($data);
            if($res){
                return $this->success('操作成功！！！');
            }else{
                return $this->error('操作失败！！！');
            }
        }else{
            $oid=I('get.oid');
            $data=M('invoice')->where(['order_id'=>$oid])->find();
            $this->assign('data',$data);
            return $this->fetch();
        }
    }
    
    
     //开票时间
    function changetime(){
        
     header("Content-type: text/html; charset=utf-8");

    }
    
    
    public function export_invoice()
    {
    header("Content-type: text/html; charset=utf-8");

    }

}
