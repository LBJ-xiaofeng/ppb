<?php
namespace app\ppb\controller;

use think\Controller;
class Ppb extends Base{
    public function saomiao(){
        return $this->fetch();
    }
    public function about(){
        return $this->fetch();
    }
    public function baoyang(){
        return $this->fetch();
    }
    public function gj_allorder(){
        return $this->fetch();
    }
    public function changjian(){
        return $this->fetch();
    }
    public function daifahuo(){
        return $this->fetch();
    }
    public function ddxiang(){
        return $this->fetch();
    }
    public function ddxq(){
        return $this->fetch();
    }
    public function detail(){
        return $this->fetch();
    }
    public function dizhi(){
        return $this->fetch();
    }
    public function dpj(){
        return $this->fetch();
    }
    public function dsh(){
        return $this->fetch();
    }
    public function fwfw(){
        return $this->fetch();
    }
    public function fwjs(){
        return $this->fetch();
    }
    public function fwzx(){
        return $this->fetch();
    }
    public function gj_baoyang(){
        return $this->fetch();
    }
    public function gj_ckdd(){
        return $this->fetch();
    }
    public function gj_ckdd2(){
        return $this->fetch();
    }
    public function gj_cxfj(){
        return $this->fetch();
    }
    public function gj_czgl(){
        return $this->fetch();
    }
    public function gj_ddgl(){
        return $this->fetch();
    }
    public function gj_dsh(){
        return $this->fetch();
    }
    public function gj_flgl(){
        return $this->fetch();
    }
    public function gj_flgl1(){
        return $this->fetch();
    }
    public function gj_gzgl(){
        return $this->fetch();
    }
    public function gj_index(){
        return $this->fetch();
    }
    public function gj_jdgl(){
        return $this->fetch();
    }
    public function gj_jdgl2(){
        return $this->fetch();
    }
    public function gj_jdgl3(){
        return $this->fetch();
    }
    public function gj_jdgl4(){
        return $this->fetch();
    }
    public function gj_jdgllist(){
        return $this->fetch();
    }
    public function gj_ppgl(){
        return $this->fetch();
    }
    public function gj_wpgl(){
        return $this->fetch();
    }
    public function gj_yiwancheng(){
        return $this->fetch();
    }
    public function gj_yjgl(){
        return $this->fetch();
    }
    public function gj_zjy(){
        return $this->fetch();
    }
    public function gj_zjyccxq(){
        return $this->fetch();
    }
    public function goodsxq(){
        return $this->fetch();
    }
    public function guanli(){
        return $this->fetch();
    }
    public function gwc(){
        return $this->fetch();
    }
    public function index(){
        return $this->fetch();
    }
    public function jmzx(){
        return $this->fetch();
    }
    public function login(){
        return $this->fetch();
    }
    public function login2(){
        return $this->fetch();
    }
    public function medongxi(){
        return $this->fetch();
    }
    public function member(){
        return $this->fetch();
    }
    public function pingjia(){
        return $this->fetch();
    }
    public function shezhi(){
        return $this->fetch();
    }
    public function shixiao(){
        return $this->fetch();
    }
    public function tianjia(){
        return $this->fetch();
    }
    public function weixin(){
        return $this->fetch();
    }
    public function wjmm(){
        return $this->fetch();
    }
    public function xiangqing(){
        return $this->fetch();
    }
    public function xieyi(){
        return $this->fetch();
    }
    public function xiugaixinxi(){
        return $this->fetch();
    }
    public function xuianzedizhi(){
        return $this->fetch();
    }
    public function yaoqing(){
        return $this->fetch("User/jssuser");
    }
    public function yjfk(){
        return $this->fetch();
    }
    public function youhui1(){
        return $this->fetch();
    }
    public function yue(){
        return $this->fetch();
    }
    public function yycg(){
        return $this->fetch();
    }
    public function yyfk(){
        return $this->fetch();
    }
    public function yyqj(){
        return $this->fetch();
    }
    public function yyxd(){
        return $this->fetch();
    }
    public function yyxd0(){
        return $this->fetch();
    }
    public function yyxd2(){
        return $this->fetch();
    }
    public function yyxd3(){
        return $this->fetch();
    }
    public function yyxd4(){
        return $this->fetch();
    }
    public function yyxdxuanze(){
        return $this->fetch();
    }
    public function zhuce(){
        $uid=I('last_uid');
        // print_r($uid);die;
        $this->assign('uid',$uid);
        return $this->fetch();
    }
    public function zjjc(){
        return $this->fetch();
    }

}