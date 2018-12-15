<?php
namespace app\admin\controller;

use think\Controller;
use think\AjaxPage;
use think\Page;

class Distribut extends Base {
    //分销列表
    public function goods_list(){
        if(IS_POST){
            $start_time=strtotime(I('start_time'));
            $end_time=strtotime(I('end_time'));
            $count=M('users')->where('first_leader','not null')->where('first_leader','gt',0)->where('distribut_time','>',$start_time)->where('distribut_time','<',$end_time)->count();
            $Page  = new Page($count,20);
            $list=M('users')->where('reg_time','>',$start_time)->where('reg_time','<',$end_time)->field('user_id,mobile,nickname,nickname,user_money,reg_time,first_leader')->order('user_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        }else{
           $count=M('users')->where('first_leader','not null')->where('first_leader','gt',0)->count();
            $Page  = new Page($count,20);
            $list=M('users')->field('user_id,mobile,nickname,nickname,user_money,reg_time,first_leader')->order('user_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        }
        $show  = $Page->show();
        $this->assign('show',$show);
        $this->assign('start_time',date('Y-m-d H:i:s',strtotime('-7 day')));
        $this->assign('end_time',date('Y-m-d H:i:s',time()));
        $this->assign('list',$list);
        $this->assign('count',$count);
        $this->assign('pager',$Page);
        return $this->fetch();
    }
    //代理申请列表
    public function distributor_list(){
        $type=I('type',1);
        $start=I('start_time');
        $endt=I('end_time');
        if(!empty($start) && !empty($endt)){
            $start_time=strtotime($start);
            $end_time=strtotime($endt);
        }else{
            $start_time=time()-(3600*24*7);
            $end_time=time();
        }
        $count=M('users')->where(['is_distribut'=>$type])->where('distribut_time','>',$start_time)->where('distribut_time','<',$end_time)->count();
        $Page  = new Page($count,20);
        $show  = $Page->show();
        $list=M('users')->where(['is_distribut'=>$type])->where('distribut_time','>',$start_time)->where('distribut_time','<',$end_time)->order('user_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('show',$show);
        $this->assign('start_time',date('Y-m-d H:i:s',strtotime('-7 day')));
        $this->assign('end_time',date('Y-m-d H:i:s',time()));
        $this->assign('list',$list);
        $this->assign('count',$count);
        $this->assign('pager',$Page);
        return $this->fetch();
    }
//    代理申请审核
     public function handle_distribut(){
         if(IS_POST){
             $user_id=I('post.uid');
             $type=I('post.type');
             $to=I('post.email');
             $name=I('post.name');
             $title='审核代理邮件通知';
             $res=M('users')->where(['user_id'=>$user_id])->setField('is_distribut',$type);
             if($res){
                 if($type==2){
                     $message='你好！用户名为：'.$name.'（先生/女士），恭喜您，通过我们的审核成为代理，感谢您的使用，希望你使用愉快！';
                 }elseif($type==3){
                     $message='你好！用户名为：'.$name.'（先生/女士），很抱歉，没有通过我们的审核成为代理，感谢您的使用，希望你使用愉快！';
                 }
                 $rest=send_email($to, $title, $message);
                 if($rest){
                     return json_encode(array('status'=>1,'msg'=>'审核成功','data'=>''));
                 }else{
                     return json_encode(array('status'=>0,'msg'=>'审核失败','data'=>''));
                 }
             }else{
                 return json_encode(array('status'=>0,'msg'=>'网络错误','data'=>''));
             }
         }
         $uid=I('id');
         $user=M('users')->where(['user_id'=>$uid])->field('user_id,nickname,mobile,email,reg_time,distribut_time,user_money,is_distribut,distribut_time,first_leader,second_leader,third_leader')->find();
         $this->assign('user',$user);
         return $this->fetch();
     }
    //分成日志
    public function rebate_log(){
        if(IS_POST){
            $start_time=strtotime(I('start_time'));
            $end_time=strtotime(I('end_time'));
        }else{
            $start_time=time()-(3600*24*100);
            $end_time=time();
        }
        $count=M('distribution_log')->where('add_time','>',$start_time)->where('add_time','<',$end_time)->count();
        $Page  = new Page($count,20);
        $show  = $Page->show();
        $data=M('distribution_log')->where('add_time','>',$start_time)->where('add_time','<',$end_time)->order('add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('data',$data);
        $this->assign('show',$show);
        $this->assign('start_time',date('Y-m-d H:i:s',strtotime('-7 day')));
        $this->assign('end_time',date('Y-m-d H:i:s',time()));
        $this->assign('count',$count);
        $this->assign('pager',$Page);
        return $this->fetch();
    }
    //所有下属
    public function getMyDistribution()
    {
        $uid=I('id');
        $count=M('users')->where('first_leader',$uid)->count();
        $Page  = new Page($count,20);
        $list=M('users')->where('first_leader',$uid)->field('user_id,mobile,nickname,nickname,user_money,reg_time,first_leader')->order('user_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $show  = $Page->show();
        $this->assign('show',$show);
        $this->assign('start_time',date('Y-m-d H:i:s',strtotime('-7 day')));
        $this->assign('end_time',date('Y-m-d H:i:s',time()));
        $this->assign('list',$list);
        $this->assign('count',$count);
        $this->assign('pager',$Page);
        return $this->fetch('Distribut/goods_list');
    }


}