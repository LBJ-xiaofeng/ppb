<?php
namespace app\ppb\controller;

use think\Controller;
use app\common\logic\OrderLogic;
use app\common\logic\UsersLogic;
class Order extends Base{
    //我的优惠券
    public function myCoupon()
    {
        $uid=I('get.user_id');
        if(!$uid){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'无效参数'));
        }
        $sql="select a.*,b.use_end_time,b.money,b.condition,b.name from tp_coupon_list as a left join tp_coupon as b on a.cid=b.id where a.uid=".$uid;
        $result=M('coupon_list')->query($sql);
        foreach($result as $k=>$v){
            $result[$k]['use_end_time']=date('Y-m-d H:i',$v['use_end_time']);
        }
        $data=array();
        if($result){
            $now=time();
            foreach($result as $k=>$val){
                if($val['status']==0){
                    if($now-strtotime($val['use_end_time'])<0){
                        $val['data']='normal';//正常
                    }else{
                        $val['data']='overdue';//过期
                    }
                }else{
                    $val['data']='used';//已使用
                }
                $data[$val['data']][] = $val;
            }
            if(empty($data['normal']) || !isset($data['normal'])){
                $data['normal']=[];
            }
            if(empty($data['overdue']) || !isset($data['overdue'])){
                $data['overdue']=[];
            }
            if(empty($data['used']) || !isset($data['used'])){
                $data['used']=[];
            }
            return json_encode(array('status'=>1,'data'=>$data,'msg'=>'优惠券获取成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'您暂无优惠券'));
        }
    }
       /*
     * 兑换优惠券
     */
    public function cartCouponExchange()
    {
        $coupon_code = input('coupon_code');
        $user_id=I('user_id');
        if (!$user_id){
            return json_encode(['status' => 0, 'msg' => "登录超时请重新登录!", 'data' => '']);
        }
        if (!$coupon_code) {
            return json_encode(['status' => 0, 'msg' => '请输入优惠券券码', 'data' => '']);
        }
        $coupon_list = M('coupon_list')->where('code', $coupon_code)->find();
        if (empty($coupon_list)){
            return json_encode(['status' => 0, 'msg' => '优惠券码不存在', 'data' => '']);
        }
        if ($coupon_list['status'] > 0) {
            return json_encode(['status' => 0, 'msg' => '该优惠券已无效', 'data' => '']);
        }
        if ($coupon_list['uid'] > 0) {
            return json_encode(['status' => 0, 'msg' => '该优惠券已兑换', 'data' => '']);
        }
        $coupon = M('coupon')->where(['id'=>$coupon_list['cid']])->find(); // 获取优惠券类型表
        if (time() > $coupon['use_end_time']) {
            return json_encode(['status' => 0, 'msg' => '优惠券已失效或过期', 'data' => '']);
        }
        $do_exchange = M('coupon_list')->where('id',$coupon_list['id'])->update(['uid'=>$user_id,'send_time'=>time()]);
        if ($do_exchange !== false) {
            M('coupon')->where(['id'=>$coupon_list['cid']])->setInc('send_num');
            return json_encode(['status' => 1, 'msg' => '兑换成功', 'data' => '']);
        } else {
            return json_encode(['status' => 0, 'msg' => '兑换失败', 'data' => '']);
        }
    }
    /*
   * 微信充值到余额
   */
    public function topUp(){
        $uid=I('user_id');
        $type=1;
        $goods_price=I('money');
        $oid=uniqid('wx');
        $user=M('users')->where(['user_id'=>$uid])->getField('nickname');
        $data['user_id']=$uid;
        $data['nickname']=$user;
        $data['order_sn']=$oid;
        $data['account']=$goods_price;
        $data['ctime']=time();
        $data['pay_code']='weixin';
        $data['pay_name']='微信支付';
        $data['pay_status']=0;
        // print_r($data);die;
        $res=M('recharge')->add($data);
        if(!$res){
            return json_encode(array('status'=>0,'msg'=>'网络错误，请重试','data'=>''));
        }
        $wxpay=new Dpay();
        $goods_info='微信充值'.$goods_price.'元';
        $wxpay->index($oid,$goods_price,$goods_info,$type);
    }
    /*
     * 微信支付
     */
    public function orderPay(){
        $oid=I('order_id');
        $type=I('type');//支付方式，0微信，1钱包余额
        $order=M('order')->where(['order_id'=>$oid])->field('order_sn,goods_price,address,cat_id,attr_id,brand_id')->find();
        switch ($type) {
            case '1';//如果支付方式为余额
                $order=$this->payCod($order['order_sn'],$order['goods_price']);
                echo $order;
                break;
            case '0';
                $data=$this->getOrderData($order['cat_id'],$order['attr_id'],$order['brand_id'],$order['article_id']);
                $goods_info='支付'.$data['mobile_name'].'-'.$data['attr_name'].'-'.$data['brand_name'];
                $wxpay=new Dpay();
                $wxpay->index($order['order_sn'],$order['goods_price'],$goods_info,0);
        }

    }
    //余额支付方法
    public function payCod($out_trade_no, $total_fee)
    {
        $uid=M('order')->where(['order_sn'=>$out_trade_no])->getField('user_id');

        $user_money=M('users')->where(['user_id'=>$uid])->getField('user_money');
        if($user_money<$total_fee){
            return json_encode(array('status'=>0,'msg'=>'余额不足','data'=>''));
        }else{
            $data['user_money']=$user_money-$total_fee;
            $result=M('users')->where(['user_id'=>$uid])->save($data);
            if(!$result){
                return json_encode(array('status'=>0,'msg'=>'支付失败','data'=>''));
            }else{
                $order['pay_status']=1;
                $order['pay_time']=time();
                M('order')->where(['order_sn'=>$out_trade_no])->save($order);
                return json_encode(array('status'=>1,'msg'=>'支付成功','data'=>''));
            }
        }
    }
 
    /*
*添加评论
*/
    public function add_comment()
    {
        $add['user_id'] = I('user_id');
        $add['order_id'] = I('order_id/d');
        if (!$add['order_id'] || !$add['user_id']) {
            return json_encode(array('status' => 0, 'data' => '', 'msg' => '参数无效'));
        }
        $data = M('users')->where(['user_id' => I('user_id')])->field('email,nickname')->find();
        $add['email'] = $data['email'];
        $add['username'] = $data['nickname'];
        $add['content'] = I('content');
        $add['add_time'] = time();
        $add['parent_id'] = 0;
        $add['is_anonymous'] = 0;
        $add['is_show'] = 1; //默认显示
        $add['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $file = request()->file('comment_img');
        if (!empty($file) || isset($file)) {
            $up=new Order();
            $info = $up->upload($file);
            $add['img'] = serialize($info);
        }
        // print_r($add);die;
        $row = M('comment')->add($add);
        if ($row) {
            //更新订单状态
            M('order')->where(array('order_id'=>$add['order_id'],'user_id'=>$add['user_id']))->save(array('order_status'=>5,'is_comment'=>1));
            return json_encode(array('status' => 1, 'msg' => '评论成功', 'data' => $row));
        } else {
            return json_encode(array('status' => 0, 'msg' => '评论失败', 'data' => ''));
        }
    }
    public function upload($file)
    {
        if ($file) {

            foreach ($file as $files) {
                $info[]= $files->move('public/upload/qr_code');
            }

            foreach($info as $infos){
                $img[]=$this->request->domain() . '/public/upload/qr_code/' . $infos->getSaveName();
            }
            return $img;
        }else{
            return false;
        }
    }
    //确认收货
    public function order_confirm()
    {
        $id = I('post.order_id', 0);
        $user_id=I('post.user_id/d', 0);
        if(!$id || !$user_id){
            return json_encode(array('status'=>0,'msg'=>'参数错误','data'=>''));
        }
        $data = confirm_order($id, $user_id);
        $this->ajaxReturn($data);
    }
        /*
     * 取消订单
     */
    public function cancel_order(){
        $uid = I('get.user_id/d');
        $id = I('get.order_id');
        $order = M('order')->where(array('order_id'=>$id,'user_id'=>$uid))->find();
        if(empty($order)){
            return json_encode(array('status'=>0,'msg'=>'订单不存在','data'=>''));
        }
        if($order['is_cancel'] == 1){
            return json_encode(array('status'=>0,'msg'=>'该订单已取消','data'=>''));
        }
        $res=M('order')->where(['user_id'=>$uid,'order_id'=>$id])->setField('is_cancel',1);
        if($res){
            return json_encode(array('status'=>1,'msg'=>'取消成功','data'=>''));
        }else{
            return json_encode(array('status'=>0,'msg'=>'取消失败','data'=>''));
        }
    }

    //订单列表
    public function orderList()
    {
        $type = I('get.type/d', 0);
        $uid = I('get.user_id');
        if (!$uid) {
            return json_encode(array('status' => 0, 'data' => '', 'msg' => '参数无效'));
        }
        if ($type == 0) {            //定损中，order_status=1
            $order = M('order')->where(['deleted' => 0,'is_cancel'=>0, 'user_id' => $uid, 'order_status' => 1])->field('order_id,add_time,order_sn,consignee,mobile,goods_num,address,cat_id,attr_id,brand_id,goods_price,is_comment,pay_status')->select();
        } elseif ($type == 1) {       //保养中，order_status=2
            $order = M('order')->where(['deleted' => 0, 'user_id' => $uid, 'is_cancel'=>0,'order_status' => 2])->field('order_id,add_time,order_sn,consignee,mobile,goods_num,address,cat_id,attr_id,brand_id,goods_price,is_comment,pay_status')->select();
        } elseif ($type == 2) {       //待取回，order_status=3
            $order = M('order')->where(['deleted' => 0, 'user_id' => $uid,'is_cancel'=>0, 'order_status' => 3])->field('order_id,add_time,order_sn,consignee,mobile,goods_num,address,cat_id,attr_id,brand_id,goods_price,is_comment,pay_status')->select();
        } elseif ($type == 3) {       //待评价，order_status=4
            $order = M('order')->where(['deleted' => 0, 'user_id' => $uid,'is_cancel'=>0, 'order_status' => 4])->field('order_id,add_time,order_sn,consignee,mobile,goods_num,address,cat_id,attr_id,brand_id,goods_price,is_comment,pay_status')->select();
        }
        foreach ($order as $k => $v) {
            $order[$k]['add_time']=date('Y-m-d H:i:s',$v['add_time']);
            $data = $this->getOrderData($v['cat_id'], $v['attr_id'], $v['brand_id'], $v['article_id']);
            $order[$k]['mobile_name'] = $data['mobile_name'];
            $order[$k]['attr_name'] = $data['attr_name'];
            $order[$k]['brand_name'] = $data['brand_name'];
            $order[$k]['cat_name'] = $data['cat_name'];
        }
//        print_r($order);die;
        if ($order) {
            return json_encode(array('status' => 1, 'data' => $order, 'msg' => '获取成功'));
        } else {
            $data = [];
            return json_encode(array('status' => 0, 'data' => $data, 'msg' => '没有订单'));
        }
    }
    //订单详情
    public function orderDetail()
    {
        $oid=I('order_id');
        $data=M('order')->where('order_id',$oid)->field('order_sn,add_time,consignee,mobile,province,city,twon,address,cat_id,attr_id,brand_id,goods_price,goods_num,order_prom_type,order_prom_amount,integral,transaction_id,order_status,wc_status,hg_status')->find();
// print_r(unserialize($data['transaction_id']));die;
        $data['mobile_name'] = M('goods_category')->where('id', $data['cat_id'])->getField('mobile_name');
        $data['attr_name'] = M('goods_attr')->where('goods_attr_id', $data['attr_id'])->getField('attr_name');
        $data['brand_name'] = M('brand')->where('id', $data['brand_id'])->getField('name');
        $data['add_time']=date('Y-m-d H:i:s',$data['add_time']);
        $data['shop_address']=tpCache('shop_info.address');
        $data['shop_mobile']=tpCache('shop_info.mobile');
        $data['shop_name']=tpCache('shop_info.contact');
        $data['order_status']=getOrderStatus($data['order_status']);
        if($data['goods_price']==0){
            $data['goods_price']='待定';
        }
         
            $data['order_prom_type']=unserialize($data['order_prom_type']);//客服上传的定损照片
            $num=explode(',',$data['order_prom_amount']);

            foreach ($num as $k => $v) {
                $data['checkedimg'][]=$data['order_prom_type'][$v];//客户选择的维护照片
            }
            // print_r($data['checkedimg']);die;

            $data['integral']=unserialize($data['integral']);//质检员上传的图片
            $money=M('order_money')->where('oid',$oid)->field('title,money')->select();
            if($money){
                $data['moneylist']=$money;
            }else{
                $data['moneylist']='';
            }
        return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
    }
    //获取订单其中信息
    public function getOrderData($cat_id,$attr_id,$brand_id,$article_id){
        $data['mobile_name']=M('goods_category')->where('id',$cat_id)->getField('mobile_name');
        $data['attr_name']=M('goods_attr')->where('goods_attr_id',$attr_id)->getField('attr_name');
        $data['brand_name']=M('brand')->where('id',$brand_id)->getField('name');
        $data['cat_name']=M('article_cat2')->where('cat_id',$article_id)->getField('cat_name');
        return $data;
    }
    //客户选择维护图片
    public function changeimage(){
        $num=I('number');
        if(!$num){
            return json_encode(['status'=>0,'msg'=>'请选择']);
        }
        $oid=I('order_id');
        $amout=M('order')->where(['order_id'=>$oid])->getField('order_prom_amount');
        if(!empty($amout)){
             $amout=explode(',',$amout);
             $nums=explode(',',$num);
             $numt=array_unique(array_merge($amout,$nums));
             $num=implode(',',$numt);
        }
        if(!$oid){
            return json_encode(['status'=>0,'msg'=>'参数错误']);
        }
        if(is_array($num)){
            $num=implode(',',$num);
        }
        $res=M('order')->where('order_id',$oid)->setField('order_prom_amount',$num);
        if($res){
            return json_encode(['status'=>1,'msg'=>'选择成功']);
        }else{
            return json_encode(['status'=>0,'msg'=>'请重新选择']);
        }

    }
    //客户提交审核结果
    public function submineset(){
        $data['order_id']=I('order_id');//订单id
        $data['action_user']=I('user_id');//客户id
        $data['type']=I('type');//质检状态，0不合格，1合格
        if($data['type']==1){
            $data['action_note']='客户检测合格';
        }else{
            $data['action_note']=I('msg');//不合格理由
        }
        $data['add_time']=time();//检测时间
        $res=M('order_result')->add($data);
        if($data['type']==0){
            M('order')->where(['order_id'=>$data['order_id']])->setField('wc_status',$data['type']);
        }
        if($res){
            return json_encode(array('status' => 1, 'data' => '', 'msg' => '操作成功'));
        }else{
            return json_encode(array('status' => 0, 'data' => '', 'msg' => '操作失败'));
        }
    }

}