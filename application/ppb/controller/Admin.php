<?php
namespace app\ppb\controller;

use think\Controller;

class Admin extends Base{
    public function aaa(){
        $order = M('order')->where("order_id = 1598")->field('transaction_id,order_prom_amount')->find();
        $transaction=unserialize($order['transaction_id']);
        $num=explode(',',$order['order_prom_amount']);
        foreach ($num as $k => $v) {
            $img[]=$transaction[$v];
        }

        print_r($img);die;
    }
    //后台账号前端登录扫码
    public function adminLogin()
    {
        if(IS_POST){
            $condition['user_name'] = I('post.username/s');
            $condition['password'] = I('post.password/s');
            if(!empty($condition['user_name']) || !empty($condition['password'])){
                $condition['password'] = encrypt($condition['password']);
                $admin_info = M('admin')->join(PREFIX.'admin_role', PREFIX.'admin.role_id='.PREFIX.'admin_role.role_id','INNER')->where($condition)->find();
                if(is_array($admin_info)){
                    cookie('admin',$admin_info['admin_id']);
                    cookie('role_id',$admin_info['role_id']);
                    $data=array('admin_id'=>$admin_info['admin_id'],'role_id'=>$admin_info['role_id']);
                    M('admin')->where("admin_id = ".$admin_info['admin_id'])->save(array('last_login'=>time(),'last_ip'=>  request()->ip()));
                    $this->adminLog($admin_info['admin_id'],'前台账号登录');
                    return json_encode(array('status'=>1,'msg'=>'登陆成功','data'=>$data));
                }else{
                    return json_encode(array('status'=>0,'msg'=>'账号密码不正确'));
                }
            }else{
                return json_encode(array('status'=>0,'msg'=>'请填写账号密码'));
            }
        }else{
            return $this->fetch('admin/admin_Login');
        }

    }

    //操作日志
    function adminLog($aid,$log_info){
        $add['log_time'] = time();
        $add['admin_id'] = $aid;
        $add['log_info'] = $log_info;
        $add['log_ip'] = request()->ip();
        $add['log_url'] = request()->baseUrl() ;
        M('admin_log')->add($add);
    }

    //登录账号调用微信扫一扫
    public function wxScan(){
        $rid=cookie('role_id');
        $config=getMchid();
        $appid = $config['appid'];  //微信支付申请对应的公众号的APPID
        $appKey = $config['appsecret'];   //微信支付申请对应的公众号的APP Key
        $jssdk = new Jssdk($appid, $appKey);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('data',$signPackage);
        $this->assign('rid',$rid);
        return $this->fetch();
    }
    //查询是否为本人订单
    public function is_myorder(){
        $oid=I('order_id');
        $adminid=cookie('admin');
        $admin = M('order')->where(['order_id' => $oid])->field('first_admin,seacond_admin,third_admin,four_admin,five_admin,six_admin')->find();
        $where['role_id']=array('in','1,17,19');
        $num=M('admin')->where($where)->field('admin_id')->select();
        foreach($num as $k=>$v){
            $arr[]=$v['admin_id'];
        }
        // $arr=array('admin1'=>1,'admin2'=>17);
        $admin=array_unique(array_merge($arr,$admin));

        $admin=explode(',',implode(',',$admin));
        // print_r($admin);die;
        if(in_array($adminid,$admin)){
              return json_encode(array('status'=>1));
        }else{
            return json_encode(array('status'=>0));
        }
    }
    //修改订单
    public function edit_order(){
        $oid=I('order_id');
        $rid=I('role_id');
        $this->assign('oid',$oid);
        $this->assign('rid',$rid);
        if($rid==10){
             $data=M('order')->where('order_id',$oid)->field('first_start_time,first_end_time,first_content,cat_id,attr_id,brand_id')->find();
            $data['mobile_name'] = M('goods_category')->where('id', $data['cat_id'])->getField('mobile_name');
            $data['attr_name'] = M('goods_attr')->where('goods_attr_id', $data['attr_id'])->getField('attr_name');
            $data['brand_name'] = M('brand')->where('id', $data['brand_id'])->getField('name');
             $this->assign('data',$data);
             return $this->fetch('Admin/edit_order1');
        }elseif($rid==11){
            $data=M('order')->where('order_id',$oid)->field('seacond_start_time,seacond_end_time,seacond_content,cat_id,attr_id,brand_id')->find();
            $data['mobile_name'] = M('goods_category')->where('id', $data['cat_id'])->getField('mobile_name');
            $data['attr_name'] = M('goods_attr')->where('goods_attr_id', $data['attr_id'])->getField('attr_name');
            $data['brand_name'] = M('brand')->where('id', $data['brand_id'])->getField('name');
            $this->assign('data',$data);
            return $this->fetch('Admin/edit_order2');
        }elseif($rid==12){
            $data=M('order')->where('order_id',$oid)->field('third_start_time,third_end_time,third_content,cat_id,attr_id,brand_id')->find();
            $data['mobile_name'] = M('goods_category')->where('id', $data['cat_id'])->getField('mobile_name');
            $data['attr_name'] = M('goods_attr')->where('goods_attr_id', $data['attr_id'])->getField('attr_name');
            $data['brand_name'] = M('brand')->where('id', $data['brand_id'])->getField('name');
            $this->assign('data',$data);
            return $this->fetch('Admin/edit_order3');
        }elseif($rid==13){
            $data=M('order')->where('order_id',$oid)->field('four_start_time,four_end_time,four_content,cat_id,attr_id,brand_id')->find();
            $data['mobile_name'] = M('goods_category')->where('id', $data['cat_id'])->getField('mobile_name');
            $data['attr_name'] = M('goods_attr')->where('goods_attr_id', $data['attr_id'])->getField('attr_name');
            $data['brand_name'] = M('brand')->where('id', $data['brand_id'])->getField('name');
            $this->assign('data',$data);
            return $this->fetch('Admin/edit_order4');
        }elseif($rid==14){
            $data=M('order')->where('order_id',$oid)->field('five_start_time,five_end_time,five_content,cat_id,attr_id,brand_id')->find();
            $data['mobile_name'] = M('goods_category')->where('id', $data['cat_id'])->getField('mobile_name');
            $data['attr_name'] = M('goods_attr')->where('goods_attr_id', $data['attr_id'])->getField('attr_name');
            $data['brand_name'] = M('brand')->where('id', $data['brand_id'])->getField('name');
            $this->assign('data',$data);
            return $this->fetch('Admin/edit_order5');
        }elseif($rid==15){
            $data=M('order')->where('order_id',$oid)->field('six_start_time,six_end_time,six_content,cat_id,attr_id,brand_id')->find();
            $data['mobile_name'] = M('goods_category')->where('id', $data['cat_id'])->getField('mobile_name');
            $data['attr_name'] = M('goods_attr')->where('goods_attr_id', $data['attr_id'])->getField('attr_name');
            $data['brand_name'] = M('brand')->where('id', $data['brand_id'])->getField('name');
            $this->assign('data',$data);
            return $this->fetch('Admin/edit_order6');
        }elseif($rid==1){//超级管理员
            $order=M('order')->where('order_id',$oid)->find();
            $data=$this->getOrderData($order['cat_id'],$order['attr_id'],$order['brand_id']);
            $order['mobile_name']=$data['mobile_name'];
            $order['attr_name']=$data['attr_name'];
            $order['brand_name']=$data['brand_name'];
            $order['order_prom_type']=unserialize($order['order_prom_type']);
            $num=explode(',',$order['order_prom_amount']);
                foreach ($num as $k => $v) {
                    $order['checkedimg'][]=$order['order_prom_type'][$v];//客户选择的维护照片
                }
            $order['integral']=unserialize($order['integral']);
            if($order['goods_price']==0){
                $order['goods_price']="待定";
            }
            $order['order_status']=getOrderStatus($order['order_status']);
            $order['pay_status']=getPayStatus($order['pay_status']);
            if($order['pay_time']>0){
                $order['pay_time']=date('Y/m/d',$order['pay_time']);
            }else{
                $order['pay_time']='暂未支付';
            }
            if($order['wc_status']==1 && $order['hg_status']==1){
                $order['wc_status']='已完成工作';
                $order['hg_status']='审核合格';
            }elseif($order['wc_status']==1 && $order['hg_status']==0){
                $order['wc_status']='已完成工作';
                $order['hg_status']='质检审核中...';
            }elseif($order['wc_status']==0 && $order['hg_status']==0) {
                $order['wc_status']='未完成工作';
                $order['hg_status']='进行中...';
            }
            $order['add_time']=date('Y/m/d',$order['add_time']);
            $money=M('order_money')->where(['oid'=>$oid])->field('title,money')->select();
            $result=M('order_result')->where(['order_id'=>$oid])->field('action_note,add_time')->select();
            foreach($result as $k=>$v){
                $result[$k]['add_time']=date('Y/m/d',$v['add_time']);
            }
            // print_r($order);die;
            $this->assign('order',$order);
            $this->assign('result',$result);
            $this->assign('money',$money);
            return $this->fetch('Admin/admin_order_detail');
        }elseif($rid==17){//质检员
        $action=M('order_result')->where(['order_id'=>$oid])->order('add_time desc')->select();
        foreach($action as $k=>$v){
            $action[$k]['add_time']=date('Y/m/d',$v['add_time']);
            $action[$k]['admin_name']=getAdmin($v['action_user']);
            if(!$action[$k]['admin_name']){
                $action[$k]['admin_name']='客户';
            }
        }
        $order = M('order')->where("order_id = $oid")->find();
        if($order['wc_status']==1 && $order['hg_status']==1){
                $order['wc_status']='已完成工作';
                $order['hg_status']='审核合格';
                $order['zjname']='上传快递票据照片';
            }elseif($order['wc_status']==1 && $order['hg_status']==0){
                $order['wc_status']='已完成工作';
                $order['hg_status']='质检审核中...';
                $order['zjname']='上传质检照片';
            }elseif($order['wc_status']==0 && $order['hg_status']==0) {
                $order['wc_status']='未完成工作';
                $order['hg_status']='进行中...';
                $order['zjname']='上传质检照片';
            }
        $order['pay_time']=date('Y-m-d H:i',$order['pay_time']);
        $order['add_time']=date('Y-m-d H:i',$order['add_time']);
        $order['transaction_id']=unserialize($order['transaction_id']);//业务员上传的图片
            $order['order_prom_type']=unserialize($order['order_prom_type']);//客服上传的定损照片
            $num=explode(',',$order['order_prom_amount']);
            foreach ($num as $k => $v) {
                $order['checkedimg'][]=$order['order_prom_type'][$v];//客户选择的维护照片
            }
        $order['integral']=unserialize($order['integral']);//质检员上传的图片
        $order['confirm_time']=date('Y-m-d H:i',$order['confirm_time']);
        $order['order_status']=getOrderStatus($order['order_status']);
        $data = $this->getOrderData($order['cat_id'], $order['attr_id'], $order['brand_id']);
        $order['mobile_name'] = $data['mobile_name'];
        $order['attr_name'] = $data['attr_name'];
        $order['brand_name'] = $data['brand_name'];
        $this->assign('order',$order);
        $this->assign('action',$action);
        // print_r($order);die;
        $this->assign('admin',cookie('admin'));
        return $this->fetch('Admin/zjorder');
        }elseif($rid==19){//分拣员
            $order=M('order')->where(['order_id'=>$oid])->find();
            $data = $this->getOrderData($order['cat_id'], $order['attr_id'], $order['brand_id']);
            $order['mobile_name'] = $data['mobile_name'];
            $order['attr_name'] = $data['attr_name'];
            $order['brand_name'] = $data['brand_name'];
            $order['pay_time']=date('Y-m-d H:i',$order['pay_time']);
            $order['order_status']=getOrderStatus($order['order_status']);
            $order['pay_status']=getPayStatus($order['pay_status']);
            // print_r($order);die;
            $this->assign('order',$order);
            return $this->fetch('Admin/fjorder');
        }
    }

    public function orderHandle(){
       $admin_id=cookie('admin');
        $data=I('post.');
        $res=M('order')->where('order_id',$data['order_id'])->save($data);
        if($res){
            if($data['role_id']==10){
                $this->adminLog($admin_id,'修改'.$data['order_id'].'订单步骤一');
            }elseif($data['role_id']==11){
                $this->adminLog($admin_id,'修改'.$data['order_id'].'订单步骤二');
            }elseif($data['role_id']==12){
                $this->adminLog($admin_id,'修改'.$data['order_id'].'订单步骤三');
            }elseif($data['role_id']==13){
                $this->adminLog($admin_id,'修改'.$data['order_id'].'订单步骤四');
            }elseif($data['role_id']==14){
                $this->adminLog($admin_id,'修改'.$data['order_id'].'订单步骤五');
            }elseif($data['role_id']==15){
                $this->adminLog($admin_id,'修改'.$data['order_id'].'订单步骤六');
            }
            return json_encode(array('status'=>1,'msg'=>'操作成功'));
        }else{
            return json_encode(array('status'=>0,'msg'=>'操作失败'));
        }

    }
    //分类管理
    public function categoryList(){
            $data=M('goods_category')->where(['is_show'=>1,'parent_id'=>0])->field('id,mobile_name,parent_id')->select();
           
        if($data){
            return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
        }else{
            return json_encode(array('status'=>0,'msg'=>'暂无','data'=>''));
        }
    }
    //获取编辑/删除分类
    public function getOrDelCate(){
        $id=I('id');
        $type=I('type',1);//1获取，2删除
        if($type==1){
            $data=M('goods_category')->where(['id'=>$id])->field('id,mobile_name')->find();
            return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
        }else{
            $res=M('goods_category')->where(['id'=>$id])->delete();
            if($res){
                return json_encode(array('status'=>1,'msg'=>'成功','data'=>''));
            }else{
                return json_encode(array('status'=>0,'msg'=>'失败','data'=>''));
            }
        }

    }
    //分类编辑
    public function cateEdit(){
        $id=I('id');
        $mobile_name=I('name');
        $pid=I('parent_id');
        if(!$id){
            $res=M('goods_category')->add(array('mobile_name'=>$mobile_name,'parent_id'=>$pid,'add_time'=>time()));
        }else{
            $res=M('goods_category')->where(['id'=>$id])->setField('mobile_name',$mobile_name);
        }
        if($res){
            return json_encode(array('status'=>1,'msg'=>'操作成功'));
        }else{
            return json_encode(array('status'=>0,'msg'=>'操作失败'));
        }
    }
        //种类管理,全部分类及种类
    public function speciesList(){
        $data=M('goods_category')->where(['is_show'=>1])->field('id,mobile_name,parent_id')->select();
        $attr=M('goods_attr')->field('goods_attr_id,attr_name,cat_id')->select();
        foreach($data as $k=>$v){
            foreach($attr as $k1=>$v1){
                if($v1['cat_id']==$data[$k]['id']){
                    $data[$k]['child'][]=$v1;
                }
            }
        }
        foreach($data as $k=>$v){
             if(!$v['child'] && !isset($v['child'])){
                $data[$k]['child']=array();
            }
        }
        return json_encode(array('status'=>1,'msg'=>'success','data'=>$data));
    }
    //根据分类获取所属种类
    public function cateToSpecies(){
        $id=I('id');//分类id
        if(!$id){
            return json_encode(array('status'=>0,'msg'=>'参数错误'));
        }else{
            $attr=M('goods_attr')->where(['cat_id'=>$id])->field('goods_attr_id,attr_name')->select();
            return json_encode(array('status'=>1,'msg'=>'success','data'=>$attr));
        }
    }
    //删除及获取要编辑的种类
    public function getOrDelAttr(){
        $id=I('attr_id');//种类id
        $type=I('type',1);//1获取，2删除
        if(!$id){
            return json_encode(array('status'=>0,'msg'=>'参数错误'));
        }
        if($type==1){
            $attr=M('goods_attr')->where(['goods_attr_id'=>$id])->field('goods_attr_id,attr_name')->find();
            return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$attr));
        }else{
            $res=M('goods_attr')->where(['goods_attr_id'=>$id])->delete();
            if($res){
                return json_encode(array('status'=>1,'msg'=>'成功','data'=>''));
            }else{
                return json_encode(array('status'=>0,'msg'=>'失败','data'=>''));
            }
        }
    }
    //种类编辑
    public function attrEdit(){
        $id=I('id');//种类id
        $mobile_name=I('attr_name');//种类名称
        $pid=I('cat_id');//分类id
        if(!$id){
            $res=M('goods_attr')->add(array('attr_name'=>$mobile_name,'cat_id'=>$pid,'add_time'=>time()));
        }else{
            $res=M('goods_attr')->where(['goods_attr_id'=>$id])->setField('attr_name',$mobile_name);
        }
        if($res){
            return json_encode(array('status'=>1,'msg'=>'操作成功'));
        }else{
            return json_encode(array('status'=>0,'msg'=>'操作失败'));
        }
    }
    //品牌管理，全部分类及品牌
    public function brandList(){
        $data=M('goods_category')->where(['is_show'=>1])->field('id,mobile_name,parent_id')->select();
        $attr=M('brand')->field('id,name,cat_id')->order('id desc')->select();
        foreach($data as $k=>$v){
            foreach($attr as $k1=>$v1){
                if($v1['cat_id']==$data[$k]['id']){
                    $data[$k]['child'][]=$v1;
                }
            }
        }
        foreach($data as $k=>$v){
             if(!$v['child'] && !isset($v['child'])){
                $data[$k]['child']=array();
            }
        }
        return json_encode(array('status'=>1,'msg'=>'success','data'=>$data));
    }
    //根据分类获取品牌
    public function cateToBrand(){
        $id=I('cate_id');
        if(!$id){
            return json_encode(['statsu'=>0,'msg'=>'参数错误']);
        }
        $attr=M('brand')->where(['cat_id'=>$id])->field('id,name')->order('id desc')->select();
        return json_encode(array('status'=>1,'msg'=>'success','data'=>$attr));
    }
    //删除及获取要编辑的品牌
    public function getOrDelBrand(){
        $id=I('id');//品牌id
        $type=I('type',1);//1获取，2删除
        if(!$id){
            return json_encode(array('status'=>0,'msg'=>'参数错误'));
        }
        if($type==1){
            $attr=M('brand')->where(['id'=>$id])->field('id,name')->find();
            return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$attr));
        }else{
            $res=M('brand')->where(['id'=>$id])->delete();
            if($res){
                return json_encode(array('status'=>1,'msg'=>'成功','data'=>''));
            }else{
                return json_encode(array('status'=>0,'msg'=>'失败','data'=>''));
            }
        }
    }
    //品牌编辑
    public function brandEdit(){
        $id=I('id');//品牌id
        $mobile_name=I('name');//品牌名称
        $pid=I('cat_id');//分类id
        if(!$id){
            $res=M('brand')->add(array('name'=>$mobile_name,'cat_id'=>$pid,'add_time'=>time()));
        }else{
            $res=M('brand')->where(['id'=>$id])->setField('name',$mobile_name);
        }
        if($res){
            return json_encode(array('status'=>1,'msg'=>'操作成功'));
        }else{
            return json_encode(array('status'=>0,'msg'=>'操作失败'));
        }
    }

    //意见管理
    public function getOpinion(){
        $page=I('page/d',1);
        $model=M('feedback');
        $data2=$model->field('msg_id,user_name,msg_content,msg_time,mobile')->order('msg_id desc')->limit((($page-1)*10),($page*10))->select();
        foreach($data2 as $k=>$v){
            $data2[$k]['msg_time']=date('Y/m/d',$v['msg_time']);
        }
        $count=$model->count();
        $countPage=ceil($count/10);
        $data1['data']=$data2;
        $data1['count']=$count;
        $data1['countPage']=$countPage;
        return json_encode(array('status' => 1,'data'=>$data1 ,'msg'=>'获取成功'));
    }
    //问题管理
    public function problemManagement()
    {
        $page=I('page/d',1);
        $model=M('article2');
        $data2=$model->where(['is_open'=>1])->field('article_id,title,content,add_time')->order('add_time desc')->limit((($page-1)*6),($page*6))->select();
        $count=$model->where(['is_open'=>1])->count();
        $countPage=ceil($count/6);
        foreach($data2 as $k=>$v){
            $data2[$k]['add_time']=date('Y-m-d H:i',$v['add_time']);
            $data2[$k]['content']=html_entity_decode($v['content']);
        }
        $data1['data']=$data2;
        $data1['count']=$count;
        $data1['countPage']=$countPage;
        return json_encode(array('status' => 1,'data'=>$data1 ,'msg'=>'获取成功'));
    }
    //添加问题及回复
    public function problem(){
        $data['title']=I('title');
        $data['content']=I('content');
        $data['add_time']=time();
        $r = M('article2')->add($data);
        if (!$r) {
            return json_encode(array('status' => 0,'data'=>'' ,'msg'=>'失败'));
        }else{
            return json_encode(array('status' => 1,'data'=>'' ,'msg'=>'成功'));
        }
    }
    //删除问题
    public function delProblem(){
        $id=I('id');
        $r = M('article2')->where(['article_id'=>$id])->delete();
        if (!$r) {
            return json_encode(array('status' => 0,'data'=>'' ,'msg'=>'失败'));
        }else{
            return json_encode(array('status' => 1,'data'=>'' ,'msg'=>'成功'));
        }
    }
    //修改问题
    public function editProblem(){
        $id=I('id');
        $data['title']=I('title');
        $data['content']=I('content');
        $data['add_time']=time();
        $r = M('article2')->where(['article_id'=>$id])->save($data);
        if (!$r) {
            return json_encode(array('status' => 0,'data'=>'' ,'msg'=>'失败'));
        }else{
            return json_encode(array('status' => 1,'data'=>'' ,'msg'=>'成功'));
        }
    }
    //属于该账号的订单
    public function myOrderList(){
        $admin_id=I('admin_id');
        $type=I('type',0);
        $page=I('page',1);
        if(!$admin_id){
            return json_encode(array('status' => 0,'data'=>'' ,'msg'=>'参数错误'));
        }
        if ($type == 0) {            //定损中，order_status=1
            $order = M('order')->where(['deleted' => 0, 'is_cancel'=>0,'ywy_id' => $admin_id, 'order_status' => 1])->field('order_id,order_sn,consignee,mobile,goods_num,address,cat_id,attr_id,brand_id,goods_price,add_time,order_status')->order('add_time desc')->limit(($page-1)*6,$page*6)->select();
            $count=M('order')->where(['deleted' => 0, 'is_cancel'=>0,'ywy_id' => $admin_id, 'order_status' => 0])->count();
        } elseif ($type == 1) {       //保养中，order_status=2
            $order = M('order')->where(['deleted' => 0,'is_cancel'=>0, 'ywy_id' => $admin_id, 'order_status' => 2])->field('order_id,order_sn,consignee,mobile,goods_num,address,cat_id,attr_id,brand_id,goods_price,add_time,order_status')->order('add_time desc')->limit(($page-1)*6,$page*6)->select();
            $count=M('order')->where(['deleted' => 0, 'is_cancel'=>0,'ywy_id' => $admin_id, 'order_status' => 2])->count();
        } elseif ($type == 2) {       //邮寄中，order_status=3
            $order = M('order')->where(['deleted' => 0, 'is_cancel'=>0,'ywy_id' => $admin_id, 'order_status' => 3])->field('order_id,order_sn,consignee,mobile,goods_num,address,cat_id,attr_id,brand_id,goods_price,add_time,order_status')->order('add_time desc')->limit(($page-1)*6,$page*6)->select();
            $count=M('order')->where(['deleted' => 0, 'is_cancel'=>0,'ywy_id' => $admin_id, 'order_status' => 3])->count();
        } elseif ($type == 3) {       //待评价，order_status=4
            $order = M('order')->where(['deleted' => 0, 'is_cancel'=>0,'ywy_id' => $admin_id, 'order_status' => 4])->field('order_id,order_sn,consignee,mobile,goods_num,address,cat_id,attr_id,brand_id,goods_price,add_time,order_status')->order('add_time desc')->limit(($page-1)*6,$page*6)->select();
            $count=M('order')->where(['deleted' => 0, 'is_cancel'=>0,'ywy_id' => $admin_id, 'order_status' => 4])->count();
        } elseif ($type == 4) {       //全部
            $order = M('order')->where(['deleted' => 0, 'is_cancel'=>0,'ywy_id' => $admin_id])->field('order_id,order_sn,consignee,mobile,goods_num,address,cat_id,attr_id,brand_id,goods_price,add_time,order_status')->order('add_time desc')->limit(($page-1)*6,$page*6)->select();
            $count=M('order')->where(['deleted' => 0, 'is_cancel'=>0,'ywy_id' => $admin_id])->count();
        }
        foreach ($order as $k => $v) {
            $data = $this->getOrderData($v['cat_id'], $v['attr_id'], $v['brand_id']);
            $order[$k]['mobile_name'] = $data['mobile_name'];
            $order[$k]['attr_name'] = $data['attr_name'];
            $order[$k]['brand_name'] = $data['brand_name'];
            $order[$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
            $order[$k]['order_status']=getOrderStatus($v['order_status']);
        }
        $datas['countPage']=ceil($count/6);
        $datas['order']=$order;
        if ($order) {
            return json_encode(array('status' => 1, 'data' => $datas, 'msg' => '获取成功'));
        } else {
            $data = [];
            return json_encode(array('status' => 0, 'data' => $data, 'msg' => '没有订单'));
        }
    }
    //获取订单其中信息
    public function getOrderData($cat_id, $attr_id, $brand_id)
    {
        $data['mobile_name'] = M('goods_category')->where('id', $cat_id)->getField('mobile_name');
        $data['attr_name'] = M('goods_attr')->where('goods_attr_id', $attr_id)->getField('attr_name');
        $data['brand_name'] = M('brand')->where('id', $brand_id)->getField('name');
        return $data;
    }
    //修改订单
    public function editOrder(){

        if(IS_POST)
        {
            $order_id = I('order_id');
            $order['first_start_time']=date('Y/m/d',time());
            $data=M('order')->where(['order_id'=>$order_id])->getField('first_admin');
            if($data==0){
                return json_encode(array('status' => 0, 'data' => '', 'msg' => '请先分配步骤一工匠'));
            }
            $o = M('order')->where('order_id',$order_id)->save($order);
            if($o){
                return json_encode(array('status' => 1, 'data' => '', 'msg' => '成功'));
            }else{
                return json_encode(array('status' => 0, 'data' => '', 'msg' => '失败'));
            }
        }else{
            $order_id = I('order_id');
            $action=M('order_result')->where(['order_id'=>$order_id])->order('add_time desc')->count();
            $order = M('order')->where("order_id = $order_id")->find();
            $order['pay_time']=date('Y-m-d H:i',$order['pay_time']);
            $order['add_time']=date('Y-m-d H:i',$order['add_time']);
            $order['transaction_id']=unserialize($order['transaction_id']);//业务员上传的图片
            $order['order_prom_type']=unserialize($order['order_prom_type']);//客服上传的定损照片
            $num=explode(',',$order['order_prom_amount']);
            foreach ($num as $k => $v) {
                $order['checkedimg'][]=$order['order_prom_type'][$v];//客户选择的维护照片
            }
            $order['integral']=unserialize($order['integral']);//质检员上传的图片
            $order['confirm_time']=date('Y-m-d H:i',$order['confirm_time']);
            if($order['wc_status'] !=0){
                if($action>0){
                $order['checkstatus']='（被驳回）';
                $ord=M('order_result')->where(['order_id'=>$order_id])->order('add_time desc')->find();
                $order['checkmsg']=$ord['action_note'];
                $order['checktime']=date('Y/m/d',$ord['add_time']);
                }
            }else{
                if($action>0){
                $order['checkstatus']='（被驳回）';
                $ord=M('order_result')->where(['order_id'=>$order_id])->order('add_time desc')->find();
                $order['checkmsg']=$ord['action_note'];
                $order['checktime']=date('Y/m/d',$ord['add_time']);
                }else{

                $order['checkstatus']=' ';
                $order['checkmsg']=' ';
                $order['checktime']=' ';
                    
                }
            }
            $order['order_status']=getOrderStatus($order['order_status']);
            $data = $this->getOrderData($order['cat_id'], $order['attr_id'], $order['brand_id']);
            $order['mobile_name'] = $data['mobile_name'];
            $order['attr_name'] = $data['attr_name'];
            $order['brand_name'] = $data['brand_name'];
            $pid=M('goods_category')->where(['id'=>$order['cat_id']])->getField('parent_id');
            $order['parent_name']=M('goods_category')->where('id',$pid)->getField('mobile_name');
            $money=M('order_money')->where(['oid'=>$order_id])->select();
            $datas=array('order'=>$order,'money'=>$money);
            return json_encode(array('status' => 1, 'data' => $datas, 'msg' => '成功'));
        }
    }
    //生成订单二维码
    public function makecode(){
        $oid=I('oid');
        $content=$oid;
        $matrixPointSize=5;
        $matrixMarginSize=2;
        $errorCorrectionLevel='H';
        $url='public/qrcode/order_'.$oid.'.jpg';
        $qrcode_path='public/geren.jpg';
        ob_clean ();
        Vendor('phpqrcode.phpqrcode');
        $object = new \QRcode();
        $qrcode_path_new = './public/qrcode/order_'.$oid.'.jpg';//定义生成二维码的路径及名称
        $object::png($content,$qrcode_path_new, $errorCorrectionLevel, $matrixPointSize, $matrixMarginSize);
        $QR = imagecreatefromstring(file_get_contents($qrcode_path_new));//imagecreatefromstring:创建一个图像资源从字符串中的图像流
        $logo = imagecreatefromstring(file_get_contents($qrcode_path));
        $QR_width = imagesx($QR);// 获取图像宽度函数
        $QR_height = imagesy($QR);//获取图像高度函数
        $logo_width = imagesx($logo);// 获取图像宽度函数
        $logo_height = imagesy($logo);//获取图像高度函数
        $logo_qr_width = $QR_width / 4;//logo的宽度
        $scale = $logo_width / $logo_qr_width;//计算比例
        $logo_qr_height = $logo_height / $scale;//计算logo高度
        $from_width = ($QR_width - $logo_qr_width) / 2;//规定logo的坐标位置
        imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        Header("Content-type: image/png");
        //$url:定义生成带logo的二维码的地址及名称
        imagepng($QR,$url);
        return json_encode(array('status'=>1,'data'=>'http://'.$_SERVER['HTTP_HOST'].'/public/qrcode/order_'.$oid.'.jpg','msg'=>'二维码生成成功'));

    }
    //完成工作
    public function startWork(){
        $oid=I('oid');
         $data=M('order')->where(['order_id'=>$oid])->getField('transaction_id');
        $file = request()->file('comment_img');
         // print_r($file);die;
            if (!empty($file) || isset($file)) {
                $uplo=new Order();
                $info = $uplo->upload($file);
            }
        if($data && $info){
            $datas=unserialize($data);
            $datae['transaction_id']=serialize(array_merge($datas,$info));
        }elseif($info){
            $datae['transaction_id']=serialize($info);
        }else{
            $datae['transaction_id']=$data;
        }
        
        $datae['six_end_time']=date('Y/m/d',time());
        $datae['wc_status']=1;
        $res=M('order')->where('order_id',$oid)->save($datae);
        if($res){
            return json_encode(array('status' => 1, 'data' => '', 'msg' => '已完成'));
        }else{
            return json_encode(array('status' => 0, 'data' => '', 'msg' => '重试'));
        }
    }
    //分配阶段工作页面人员
    public function phaseWork(){
        //第一阶段
        $firstWork=M('admin')->where('role_id',10)->field('admin_id,user_name')->select();
        //第二阶段
        $seacondWork=M('admin')->where('role_id',11)->field('admin_id,user_name')->select();
        //第三阶段
        $thirdWork=M('admin')->where('role_id',12)->field('admin_id,user_name')->select();
        //第四阶段
        $fourWork=M('admin')->where('role_id',13)->field('admin_id,user_name')->select();
        //第五阶段
        $fiveWork=M('admin')->where('role_id',14)->field('admin_id,user_name')->select();
        //第六阶段
        $sixdWork=M('admin')->where('role_id',15)->field('admin_id,user_name')->select();
        $data=array('firstWork'=>$firstWork,'seacondWork'=>$seacondWork,'thirdWork'=>$thirdWork,'fourWork'=>$fourWork,'fiveWork'=>$fiveWork,'sixdWork'=>$sixdWork);
//        print_r($data);die;
        return json_encode(array('status' => 1, 'data' => $data, 'msg' => '获取成功'));
    }
        //提交分配流程工作人员
    public function submitPhase(){
        $oid=I('order_id');//订单id
        $order=M('order')->where(['order_id'=>$oid])->field('first_admin,seacond_admin,third_admin,four_admin,five_admin,six_admin')->find();
        I('first_admin')?$data['first_admin']=I('first_admin'):$data['first_admin']=$order['first_admin'];
        I('seacond_admin')?$data['seacond_admin']=I('seacond_admin'):$data['seacond_admin']=$order['seacond_admin'];
        I('third_admin')?$data['third_admin']=I('third_admin'):$data['third_admin']=$order['third_admin'];
        I('four_admin')?$data['four_admin']=I('four_admin'):$data['four_admin']=$order['four_admin'];
        I('five_admin')?$data['five_admin']=I('five_admin'):$data['five_admin']=$order['five_admin'];
        I('six_admin')?$data['six_admin']=I('six_admin'):$data['six_admin']=$order['six_admin'];
        // print_r($data);die;
        $res=M('order')->where(['order_id'=>$oid])->save($data);
        if($res){
            return json_encode(array('status' => 1, 'msg' => '成功'));
        }else{
            return json_encode(array('status' => 0,  'msg' => '失败，请重试'));
        }
    }
        //待质检物件
     public function qualityInspection(){
         $order = M('order')->where(['deleted'=>0,'is_cancel'=>0,'wc_status'=>1,'order_status'=>2,'hg_status'=>0])->field('order_id,order_sn,consignee,mobile,goods_num,address,cat_id,attr_id,brand_id,goods_price,add_time,transaction_id')->select();
         foreach ($order as $k => $v) {
             $data = $this->getOrderData($v['cat_id'], $v['attr_id'], $v['brand_id']);
             $order[$k]['mobile_name'] = $data['mobile_name'];
             $order[$k]['attr_name'] = $data['attr_name'];
             $order[$k]['brand_name'] = $data['brand_name'];
             $order[$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
         }

         if ($order) {
             return json_encode(array('status' => 1, 'data' => $order, 'msg' => '获取成功'));
         } else {
             $data = [];
             return json_encode(array('status' => 0, 'data' => $data, 'msg' => '没有订单'));
         }

     }
    //质检详情
    public function qualityInspectionDetail(){
        $order_id = I('order_id');
        $action=M('order_result')->where(['order_id'=>$order_id])->order('add_time desc')->select();
        foreach($action as $k=>$v){
            $action[$k]['add_time']=date('Y/m/d',$v['add_time']);
            $action[$k]['admin_name']=getAdmin($v['action_user']);
            if(!$action[$k]['admin_name']){
                $action[$k]['admin_name']='客户';
            }
        }
        $order = M('order')->where("order_id = $order_id")->find();
        if($order['wc_status']==0){
            $order['wc_name']='未完成';
        }else{
            $order['wc_name']='已完成';
        }
        if($order['hg_status']==0){
            $order['hg_status']='不合格';
        }else{
            $order['hg_status']='合格';
        }
        $order['pay_time']=date('Y-m-d H:i',$order['pay_time']);
        $order['add_time']=date('Y-m-d H:i',$order['add_time']);
        $order['transaction_id']=unserialize($order['transaction_id']);//业务员上传的图片
            $order['order_prom_type']=unserialize($order['order_prom_type']);//客服上传的定损照片
            $num=explode(',',$order['order_prom_amount']);
            foreach ($num as $k => $v) {
                $order['checkedimg'][]=$order['order_prom_type'][$v];//客户选择的维护照片
            }
        $order['integral']=unserialize($order['integral']);//质检员上传的图片
        $order['confirm_time']=date('Y-m-d H:i',$order['confirm_time']);
        $order['order_status']=getOrderStatus($order['order_status']);
        $data = $this->getOrderData($order['cat_id'], $order['attr_id'], $order['brand_id']);
        $order['mobile_name'] = $data['mobile_name'];
        $order['attr_name'] = $data['attr_name'];
        $order['brand_name'] = $data['brand_name'];
        // $pid=M('goods_category')->where(['id'=>$order['cat_id']])->getField('parent_id');
        // $order['parent_name']=M('goods_category')->where('id',$pid)->getField('mobile_name');
        $data=array('order'=>$order,'action'=>$action);
        return json_encode(array('status' => 1, 'data' => $data, 'msg' => '操作成功'));
    }
    //质检结果
    public function qualityInspectionResult(){
        $data['order_id']=I('order_id');//订单id
        $wc=M('order')->where(['order_id'=>$data['order_id']])->getField('wc_status');
        if($wc != 1){
            return json_encode(array('status' => 0, 'data' => '', 'msg' => '订单还未完成'));
        }
        $data['action_user']=I('admin_id');//质检员id
        $data['type']=I('type');//质检状态，0不合格，1合格
        if($data['type']==1){
            $data['action_note']='检测合格';
        }else{
            $data['action_note']=I('msg');//驳回理由
        }
        $data['add_time']=time();//检测时间
        $res=M('order_result')->add($data);
        
        if($res){
            if($data['type']==0){
            M('order')->where(['order_id'=>$data['order_id']])->setField('wc_status',$data['type']);
        }elseif($data['type']==1){
            M('order')->where(['order_id'=>$data['order_id']])->save(['hg_status'=>$data['type'],'order_status'=>3]);
        }
            return json_encode(array('status' => 1, 'data' => '', 'msg' => '操作成功'));
        }else{
            return json_encode(array('status' => 0, 'data' => '', 'msg' => '操作失败'));
        }
}
//业务员提交定损照片
public function submitimg(){
    $oid=I('order_id');//订单id
        if(!$oid){
            return json_encode(array('status' => 0,'msg' => '参数错误'));
        }
    $data=M('order')->where(['order_id'=>$oid])->getField('order_prom_type');
            $img=request()->file('img');
            // print_r($img);die;
             $uplo=new Order();
             $info = $uplo->upload($img);
             // print_r($info);die;
            if($data && $info){
                $datas=unserialize($data);
                $order['order_prom_type'] = serialize(array_merge($datas,$info));
            }elseif($data && !$info){
                $order['order_prom_type'] =$data;
            }elseif(!$data && $info){
                $order['order_prom_type'] =serialize($info);
            }
            $res=M('order')->where(['order_id'=>$oid])->save($order);
            if($res){
                return json_encode(array('status' => 1,'msg' => '操作成功'));
            }else{
                return json_encode(array('status' => 0,'msg' => '操作失败'));
            }
}

    //业务员提交价格
    public function submitimgorprice(){
        $oid=I('order_id');//订单id
        if(!$oid){
            return json_encode(array('status' => 0,'msg' => '参数错误'));
        }
        if(!I('msg') && I('price')){
            return  json_encode(array('status' => 0,'msg' => '请填写价格说明'));
        }elseif(!I('price') && I('msg')){
            return  json_encode(array('status' => 0,'msg' => '请填写价格'));
        }elseif(I('msg') && I('price')){
            $data['title']=I('msg');//价格说明
            $data['money']=I('price');//价格
            $data['adminid']=I('admin_id');//操作员id
            $data['add_time']=time();
            $data['oid']=$oid;
            $res=M('order_money')->add($data);
            if($res){
                M('order')->where(['order_id'=>$oid])->setInc('goods_price',$data['money']);
                return json_encode(array('status' => 1, 'data' => '', 'msg' => '操作成功'));
            }else{
                return json_encode(array('status' => 0, 'data' => '', 'msg' => '操作失败'));
            }
        }
    }
    public function index(){
        if(IS_POST){
            $img=request()->file('img');
             $uplo=new Order();
             $info = $uplo->upload($img);
            print_r($info);die;
        }else{
            return $this->fetch();
        }
    }
    //支付时间
    public function setpaytime(){
        $oid=I('order_id');
        if(!$oid){
            return json_encode(array('status' => 0,'msg' => '参数错误'));
        }
        $pay_time=I('pay_time');
        $pay_time=strtotime($pay_time);
        if($pay_time>0){
            $data['pay_time']=$pay_time;
            $data['pay_status']=1;
            $data['order_status']=2;
        }
        $res=M('order')->where(['order_id'=>$oid])->save($data);
        if($res){
                return json_encode(array('status' => 1,'msg' => '操作成功'));
        }else{
                return json_encode(array('status' => 0,'msg' => '操作失败'));
        }
    }
    //快递单号
    public function shippingcode(){
         $oid=I('order_id');
         $code=I('code');
         if(!$oid || !$code){
            return json_encode(array('status' => 0,'msg' => '参数错误'));
         }
         $order=M('order')->where(['order_id'=>$oid])->field('wc_status,hg_status,shipping_code')->find();
         if($order['wc_status']==1 && $order['hg_status']==1){
            $data['shipping_code']=$code;
            $data['shipping_time']=time();
            $res=M('order')->where(['order_id'=>$oid])->save($data);
            if($res){
                return json_encode(array('status' => 1,'msg' => '操作成功'));
            }else{
                return json_encode(array('status' => 0,'msg' => '操作失败'));
            }
         }else{
            return json_encode(array('status' => 0,'msg' => '物品未完成'));
         }
    }

    //质检员上传图片
    public function checkimage(){
        $oid=I('order_id');
        if(!$oid){
            return json_encode(array('status' => 0,'msg' => '参数错误'));
        }
        $data=M('order')->where(['order_id'=>$oid])->getField('integral');
        $file = request()->file('img');//质检图片
            if (!empty($file) || isset($file)) {
                $uplo=new Order();
                $info = $uplo->upload($file);
            }
            if($data && $info){
                $datas=unserialize($data);
                $order['integral'] = serialize(array_merge($datas,$info));
            }elseif($data && !$info){
                $order['integral'] =$data;
            }elseif(!$data && $info){
                $order['integral'] =serialize($info);
            }
            $res=M('order')->where(['order_id'=>$oid])->save($order);
            if($res){
                return json_encode(array('status' => 1,'msg' => '操作成功'));
            }else{
                return json_encode(array('status' => 0,'msg' => '操作失败'));
            }
    }

}