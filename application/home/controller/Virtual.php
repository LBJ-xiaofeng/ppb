<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://#
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ============================================================================
 * 2016-11-21
 */
namespace app\home\controller;

use app\common\logic\CartLogic;
use app\common\logic\MessageLogic;
use app\common\logic\OrderLogic;
use app\common\logic\VirtualLogic;
use app\home\controller\Pay;
use think\Page;
use think\Db;
class Virtual extends Base
{

    public $user_id = 0;
    public $user = array();

    public function _initialize()
    {
        parent::_initialize();
//        if (session('?user')) {
//            $user = session('user');
//            $user = M('users')->where("user_id", $user['user_id'])->find();
//            session('user', $user);  //覆盖session 中的 user
//            $this->user = $user;
//            $this->user_id = $user['user_id'];
//            $this->assign('user', $user); //存储用户信息
//            $this->assign('user_id', $this->user_id);
//            //获取用户信息的数量
//			$messageLogic = new MessageLogic();
//            $user_message_count = $messageLogic->getUserMessageCount();
//            $this->assign('user_message_count', $user_message_count);
//        } else {
//            $nologin = array(
//                'login', 'pop_login', 'do_login', 'logout', 'verify', 'set_pwd', 'finished',
//                'verifyHandle', 'reg', 'send_sms_reg_code', 'identity', 'check_validate_code',
//                'forget_pwd', 'check_captcha', 'check_username', 'send_validate_code',
//            );
//            if (!in_array(ACTION_NAME, $nologin)) {
//                header("location:" . U('Home/User/login'));
//                exit;
//            }
//            if (ACTION_NAME == 'password') $_SERVER['HTTP_REFERER'] = U("Home/User/index");
//        }
//
//        //用户中心面包屑导航
//        $navigate_user = navigate_user();
//        $this->assign('navigate_user', $navigate_user);
    }
    
    public function buy_virtual(){
    	$goods = $this->check_virtual_goods();
    	$this->assign('goods',$goods);
    	return $this->fetch();
    }
    
    public function buy_step(){
    	C('TOKEN_ON',false);
    	$goods = $this->check_virtual_goods();
    	$this->assign('goods',$goods);
        return $this->fetch();
    }
    
    public function buy_step2(){
    	$order_id = I('order_id/d',0);
    	$order = M('Order')->where("order_id = $order_id")->find();
    	// 如果已经支付过的订单直接到订单详情页面. 不再进入支付页面
    	if($order['pay_status'] == 1){
    		$order_detail_url = U("Home/Order/order_detail",array('id'=>$order_id));
    		header("Location: $order_detail_url");
    	}
        $payment_where = array(
            'type'=>'payment',
            'status'=>1,
            'scene'=>array('in',array(0,2)),
            'code'=>['neq','cod']
        );
    	$paymentList = M('Plugin')->where($payment_where)->select();
    	$paymentList = convert_arr_key($paymentList, 'code');
    
    	foreach($paymentList as $key => $val)
    	{
    		$val['config_value'] = unserialize($val['config_value']);
    		if($val['config_value']['is_bank'] == 2)
    		{
    			$bankCodeList[$val['code']] = unserialize($val['bank_code']);
    		}
    	}
    
    	$bank_img = include APP_PATH . 'home/bank.php'; // 银行对应图片
    	$this->assign('paymentList',$paymentList);
    	$this->assign('bank_img',$bank_img);
    	$this->assign('order',$order);
    	$this->assign('bankCodeList',$bankCodeList);
    	$this->assign('pay_date',date('Y-m-d', strtotime("+1 day")));
        return $this->fetch();
    }
//    商品属性
    public function shopVir()
    {
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $gid=I('get.goods_id');
        $goods_type=M('goods_type')->where(['goods_id'=>$gid])->getField('id');
        $img=M('goods')->where(['goods_id'=>$gid])->getField('original_img');
        $data=M('goods_attr')->where(['goods_type_id'=>$goods_type])->field('goods_attr_id,attr_name,attr_value,attr_num,attr_price')->select();
        foreach($data as $k=>$v){
            $data[$k]['img']='http://'.$_SERVER['HTTP_HOST'].$img;
        }
        if($data){
            return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
        }else{
            return json_encode(array('status'=>0,'msg'=>'没有该商品的属性','data'=>''));
        }
    }
    //点击购买
    public function add_order(){
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $gid=I('post.goods_id');
        $good_num=I('post.goods_num',1);
        $attrid=I('post.goods_attr_id');  //属性id
        $uid=I('post.user_id');
    	$goods = $this->check_virtual_goods($gid,$good_num,$attrid,$uid);
    	$CartLogic = new OrderLogic();
    	$goods_price = $goods['goods_fee'];
        $order_sn=$CartLogic->get_order_sn();
        $user=M('users')->where(['user_id'=>$uid])->field('nickname,mobile')->find();
    	$orderArr = array('order_sn'=>$order_sn,'goods_num'=>$good_num,'goods_id'=>$gid,'user_id'=>$uid,'order_status'=>1,'pay_status'=>0,'shipping_status'=>0,'consignee'=>$user['nickname'],'mobile'=>$user['mobile'],
'goods_price'=>$goods_price,'shipping_price'=>0,'add_time'=>time(), 'order_prom_type'=>0,'order_amount'=>$goods_price,'total_amount'=>$goods_price,'spec_key'=> $goods['attr_id'],'spec_key_name'=> $goods['attr_name']
    	);
    	$order_id = M('order')->add($orderArr);
        if(!$order_id){
            return json_encode(array('status'=>0,'msg'=>'网络错误，请重试','data'=>''));
        }
        $oid=M('order')->where(['user_id'=>$uid,'order_id'=>$order_id])->field('order_id,order_sn')->find();
    	$data2['order_id']           = $oid['order_id']; // 订单id
    	$data2['user_id']           = $uid; // 客户id
    	$data2['goods_id']           = $goods['goods_id']; // 商品id
    	$data2['goods_name']         = $goods['goods_name']; // 商品名称
    	$data2['goods_sn']           = $oid['order_sn']; // 商品货号
    	$data2['goods_num']          = $goods['shop_num']; // 购买数量
    	$data2['market_price']       = $goods['market_price']; // 市场价
    	$data2['goods_price']        = $goods['goods_fee']; // 商品价
    	$data2['spec_key']           = $goods['attr_id']; // 商品规格id
    	$data2['spec_key_name']      = $goods['attr_name']; // 商品规格名称
    	$data2['member_goods_price'] = $goods['goods_fee']; // 会员折扣价
    	$data2['cost_price']         = $goods['cost_price']; // 成本价
    	$data2['give_integral']      = $goods['give_integral']; // 购买商品赠送积分
    	$data2['prom_type']          = 0; // 0 普通订单,1 限时抢购, 2 团购 , 3 促销优惠
    	$order_goods_id = M("order_goods")->add($data2);
        $data['order_id']=$oid['order_id'];
        $data['order_sn']=$oid['order_sn'];
    	if($order_goods_id){
            $sql="select id from tp_prom_goods where end_time>".time()." and goods_id=".$gid." and type in(0,1) limit 1";
            $shop=M('prom_goods')->query($sql);
            if($shop){
                M('prom_goods')->where(['id'=>$shop[0]['id']])->setInc('buy_num');
            }
            $this->ajaxReturn(['status'=>'1','msg'=>'点击购买成功，跳转','data'=>$data]);
    	}else{
    		$this->ajaxReturn(['status'=>'0','msg'=>'点击购买失败，不跳转']);
    	}
    }

    public function check_virtual_goods($gid,$num,$attr_id,$uid){
        $goods_id = $gid;
        $goods_num = $num;
        $attrid = $attr_id;
        if(empty($goods_id)){
            return json_encode(array('status'=>0,'msg'=>'请求与参数错误','data'=>''));
        }
        $goods = M('goods')->where(array('goods_id'=>$goods_id))->find();
        if($goods['store_count']+$goods['sales_sum']==0 || $goods['is_on_sale']==0 || !$goods){
            return json_encode(array('status'=>0,'msg'=>'该商品不允许购买，原因有：商品下架、不存在等','data'=>''));
        }
        if($goods_num < 1){
            return json_encode(array('status'=>0,'msg'=>'请选择购买数量','data'=>''));
        }
        $level= M('user_level')->where(['level_name'=>getUserPint($uid)])->field('level')->find();
        if(!$attrid){
            $goods['shop_num']=$goods_num;   //购买数量
            $goods['goods_fee'] = $goods['shop_price']*$level['level'];//单价(会员折扣价)
            //积分规则
            $point=M('config')->where(['name'=>'order_point'])->find();
            $goods['give_integral'] = ceil($goods['goods_fee']/50)*$point['value'];
        }else{
            $attr=M('goods_attr')->where(['goods_attr_id'=>$attrid])->field('attr_name,attr_value,attr_num,attr_price')->find();
            if($attr['attr_num']<$goods_num){
                return json_encode(array('status'=>0,'msg'=>'该商品属性库存不足,请减少购买量','data'=>''));
            }
            $goods['shop_num']=$goods_num;   //购买数量
            $goods['goods_fee'] = $attr['attr_price']*$level['level'];//单价(会员折扣价)
            //积分规则
            $point=M('config')->where(['name'=>'order_point'])->find();
            $goods['give_integral'] = ceil($goods['goods_fee']/50)*$point['value'];
            $goods['attr_id']=$attrid;
            $goods['attr_name']=$attr['attr_name'].':'.$attr['attr_value'];
        }
            return $goods;
    }
//    下单页面
    public function downOrder()
    {
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $oid=I('post.order_sn');
        if(!$oid){
            return json_encode(array('status'=>0,'msg'=>'参数错误','data'=>''));
        }
        $uid=M('order')->where(['order_sn'=>$oid])->getField('user_id');
        $address=M('user_address')->where(['user_id'=>$uid,'is_default'=>1])->field('address_id,consignee,mobile,province,city,twon,address')->find();
        $CartLogic = new OrderLogic();
        if($address){
            $address['province']=$CartLogic->getdress($address['province']);
            $address['city']=$CartLogic->getdress($address['city']);
            $address['twon']=$CartLogic->getdress($address['twon']);   //获取默认收货地址
        }
        $ordergoods=M('order_goods')->where(['goods_sn'=>$oid])->field('order_id,goods_id,goods_name,goods_sn,goods_num,goods_price,member_goods_price,spec_key_name,give_integral')->select();  //获取已选产品
        foreach($ordergoods as $k=>$v){
              $shipping=M('goods')->where(['goods_id'=>$v['goods_id']])->field('is_free_shipping,original_img')->find();
              $ordergoods[$k]['img']='http://'.$_SERVER['HTTP_HOST'].$shipping['original_img'];
              $ordergoods[$k]['is_free_shipping']=0;
        }
        $now=time();
foreach($ordergoods as $k=>$v){
    //全局通用优惠券
    $sql1="select a.id,a.cid,a.uid,a.status,b.use_end_time,b.name,b.money from tp_coupon_list as a left join tp_coupon as b on a.cid=b.id where a.status=0 and b.status=1 and b.type=2 and b.use_type=0 and b.use_end_time>".$now." and a.uid=".$uid.' and b.condition<'.$v['goods_price'];
    //指定商品优惠券
    $sql="select a.goods_id,a.coupon_id,b.use_end_time,b.name,b.money,b.condition,c.id,c.cid,c.uid,c.status from tp_goods_coupon as a left join tp_coupon as b on a.coupon_id=b.id left join tp_coupon_list as c on b.id=c.cid where c.status=0 and b.status=1 and b.type=1 and b.use_type=1 and b.use_end_time>".$now." and c.uid=".$uid." and b.condition<".$v['goods_price']." and a.goods_id=".$v['goods_id'];
    //删除购物车商品
   M('cart')->where(['goods_id'=>$v['goods_id'],'user_id'=>$uid])->delete();
}
        $result=M('coupon')->query($sql1);
        $res=M('goods_type')->query($sql);
        $coupon=array_merge($res,$result);
        if($address){
            $data['address']=$address;
        }else{
            $data['address']='';
        }
        $data['ordergoods']=[$ordergoods];
        $data['coupon']=$coupon;
        if(!empty($data)){
            return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
        }else{
            return json_encode(array('status'=>0,'msg'=>'网络错误，请重试','data'=>''));
        }
    }
    //提交订单
    public function submitOrder(){
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $oid=I('order_sn');  //订单号
        $pay_type=I('pay_type');  //支付类型(余额支付0，微信支付1)
        $address_id = I('address_id/d'); //地址id
        $coupon = I('cid',0);       //优惠券id
        $shipping = I('shipping');   //邮费
        $content = I('content');   //客户备注
        $goods_price = I('goods_price');   //商品价格
        $uid=I('user_id/d',0);    //客户id
        if(!$uid || !$oid || !$address_id || !$goods_price || $shipping){
            return json_encode(array('status'=>0,'msg'=>'参数错误1','data'=>''));
        }
        if ($address_id) {
            $address_where = ['address_id' => $address_id];
        } else {
            $address_where = ["user_id" => $uid];
        }

        $CartLogic = new OrderLogic();
        $address = Db::name('user_address')->where($address_where)->order(['is_default desc'])->find();
        if(empty($address)){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'请输入收货地址'));
        }
        $orders = M('order')->where(['order_sn'=>$oid,'user_id'=>$uid])->select();//可能包含多个订单id
        if(empty($orders)){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'订单不存在或者已取消'));
        }
        foreach($orders as $k=>$v){

            if($v['pay_status'] == 1){
                return json_encode(array('status'=>0,'data'=>'','msg'=>'该订单已支付'));
            }
            if($v['order_status'] != 1 ){   //订单状态
                return json_encode(array('status'=>0,'data'=>'','msg'=>'该订单已取消或已付款'));
            }
            if($v['user_id'] != $uid){
                return json_encode(array('status'=>0,'data'=>'','msg'=>'参数错误'));
            }
            $order['province']=$CartLogic->getdress($address['province']);
            $order['city']=$CartLogic->getdress($address['city']);
            $order['twon']=$CartLogic->getdress($address['twon']);
            $order['address']=$address['address'];
            $order['mobile']=$address['mobile'];
            $order['consignee']=$address['consignee'];
            $order['user_note']=$content;
            $order['deleted']=0;
            $order['add_time']=time();
            if(!isset($coupon) || empty($coupon)){
                $order['coupon_price']=0;
            }else{
                $coupon_price=M('coupon')->where(['id'=>$coupon])->getField('money');
                $order['coupon_price']=$coupon_price;
                M('coupon_list')->where(['cid'=>$coupon,'uid'=>$uid])->save(array('order_id'=>$v['order_id'],'use_time'=>time(),'status'=>1));
            }
            $order['shipping_price']=$shipping;
            $res=M('order')->where(['order_id'=>$v['order_id'],'user_id'=>$uid])->save($order);
        }
        if(!$res){
            return json_encode(array('status'=>0,'msg'=>'网络错误','data'=>''));
        }else{
            $pay=new Pay();
           $pay->pay_order($oid,$uid,$pay_type,$goods_price,0);
        }


    }
    /*
     * 微信充值到余额
     */
    public function topUp(){
        $tpCatch=tpCache('basic');
        if($tpCatch['top_up__off']==1){
            return json_encode(array('status'=>0,'msg'=>'提现功能暂未开通，敬请期待','data'=>''));
        }
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
        $res=M('recharge')->add($data);
        if(!$res){
            return json_encode(array('status'=>0,'msg'=>'网络错误，请重试','data'=>''));
        }
        $wxpay=new Dpay();
        $goods_info='微信充值'.$goods_price.'元';
        $wxpay->index($oid,$goods_price,$goods_info,$type);
    }


    /*
     * 微信提现
     */
        public function toMoney(){
            $tpCatch=tpCache('basic');
            if($tpCatch['withdrawal_off']==1){
                return json_encode(array('status'=>0,'msg'=>'提现功能暂未开通，敬请期待','data'=>''));
            }
            $uid=I('user_id');
            $money=I('money');
            $tpCatch=tpCache('basic');
            if($money<$tpCatch['min']){
                return json_encode(array('status'=>0,'msg'=>'最少提现'.$tpCatch['min'].'元','data'=>''));
            }
            $mon=M('user')->where(['user_id'=>$uid])->setField('user_money');
            if($mon<$tpCatch['need']){
                return json_encode(array('status'=>0,'msg'=>'余额达到'.$tpCatch['min'].'元可提现','data'=>''));
            }
            $sn=uniqid('wxtx');
            $nickname=M('users')->where(['user_id'=>$uid])->getField('nickname');
            $data['user_id']=$uid;
            $data['money']=$money;
            $data['create_time']=time();
            $data['bank_name']='微信提现';
            $data['realname']=$nickname;
            $data['status']=0;
            $data['remark']=$sn;
            $res=M('withdrawals')->add($data);
            if($res){
                $tx=new Dpay();
                $tx->txPrice($nickname,$sn,$money);
            }else{
                return json_encode(array('status'=>0,'msg'=>'网络错误，请重试','data'=>''));
            }

        }
    /*
     * 虚拟订单列表
     */
    public function virtual_list()
    {
        $type = I('get.type');
        $search_key = I('search_key');
        $virtualLogic = new \app\common\logic\VirtualLogic;
        $result = $virtualLogic->orderList($this->user_id, $type, $search_key);        
        
        $this->assign('order_status', C('ORDER_STATUS'));
        $this->assign('shipping_status', C('SHIPPING_STATUS'));
        $this->assign('pay_status', C('PAY_STATUS'));
        $this->assign('page', $result['page']->show());
        $this->assign('lists', $result['order_list']);
        $this->assign('active', 'order_list');
        $this->assign('active_status', I('get.type'));
        $this->assign('now', time());
        return $this->fetch();
    }

    /**
     * 虚拟订单详情
     */
    public function virtual_order(){
        $Order = new \app\common\model\Order();
        $VirtualLogic = new VirtualLogic();
        $order_id = I('get.order_id/d');
        $map['order_id'] = $order_id;
        $map['user_id'] = $this->user_id;
        $orderobj = $Order->where($map)->find();
        if(!$orderobj) $this->error('没有获取到订单信息');
        // 添加属性  包括按钮显示属性 和 订单状态显示属性
        $order_info = $orderobj->append(['order_status_detail','virtual_order_button','order_goods'])->toArray();
        if($order_info['order_prom_type'] != 5){   //普通订单
            $this->redirect(U('Order/order_detail',['id'=>$order_id]));
        }
        //获取订单操作记录
        $order_action = Db::name('order_action')->where(array('order_id'=>$order_id))->select();

        $data = $VirtualLogic->check_virtual_code($order_info);
        $vrorders = $data['vrorders'];
        $order_info = $data['order_info'];
        $this->assign('vrorders',$vrorders);
        $this->assign('order_status',C('ORDER_STATUS'));
        $this->assign('pay_status',C('PAY_STATUS'));
        $this->assign('order_info',$order_info);
        $this->assign('order_action',$order_action);
        return $this->fetch();
    }

    /**
     * 虚拟订单确定收货
     */
    public function virtual_confirm(){
        $order_id = I('post.order_id/d', 0);
        $data = confirm_order($order_id, $this->user_id);
        if($data['status']==1){
            Db::name('order_goods')->where(['order_id'=>$order_id])->save(['is_send'=>1]);  //订单商品状态需要更改一下，不然评价列表找不到
        }
        $this->ajaxReturn($data);
    }
}  