<?php
namespace app\home\controller;
use app\common\logic\MessageLogic;
use app\common\logic\OrderLogic;
use app\common\logic\CommentLogic;
use app\common\logic\UsersLogic;
use think\Db;
use think\Page;

class Order extends Base {
    const CHECK_TOKEN='asDFgtRewq';  //设置验证token
	public $user_id = 0;
	public $user = array();

    public function _initialize() {
        parent::_initialize();

    }

    /*
     * 订单详情
     */
    public function order_detail(){
        $id = I('get.order_id');
        if(!$id){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'参数错误'));
        }
        $data= M('order')->where('order_id',$id)->find();
            $data['add_time']=date('Y-m-d H:i:s',$data['add_time']);
            $data['shipping_time']=date('Y-m-d H:i:s',$data['shipping_time']);
            $data['confirm_time']=date('Y-m-d H:i:s',$data['confirm_time']);
            $data['order_status']=getOrderStatus($data['order_status']);
        if(!$data){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'没有获取到订单信息'));
        }else{
            return json_encode(array('status'=>1,'data'=>$data,'msg'=>'获取成功'));
        }
    }
    //订单号搜索
    public function search(){
        $id=I('order_sn');
        $result=M('order')->where('order_sn',$id)->find();
        if($result){
            return json_encode(array('status'=>1,'data'=>$result,'msg'=>'获取成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'未找到，请检查订单号'));
        }
    }
    //验收页面
    public function acceptancePage(){
        $oid=I('order_id');
        $aid=I('sorting_id');
        if(!$oid || !$aid){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'参数错误'));
        }
        $data= M('order')->where('order_id',$oid)->find();
        $data['add_time']=date('Y-m-d H:i:s',$data['add_time']);
        $data['sorting_name']=getNickname($aid);
        if(!$data){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'没有获取到订单信息'));
        }else{
            return json_encode(array('status'=>1,'data'=>$data,'msg'=>'获取成功'));
        }
    }
    //立即验收
    public function acceptance(){
        $oid=I('order_id');
        if(!$oid){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'参数错误'));
        }
        $data['shipping_code']=I('post.shipping_code');
        $data['goods_specific_heavy']=I('post.goods_specific_heavy');
        $data['goods_not_heavy']=I('post.goods_not_heavy');
        $data['goods_conform_heavy']=I('post.goods_conform_heavy');
        $data['conform_price']=I('post.conform_price');
        $data['sorting_id']=I('post.sorting_id');
        $data['integral']='H'.rand(1000,9999);
        $data['shipping_time']=time();
        $data['order_status']=2;
        $res=M('order')->where('order_id',$oid)->save($data);
        if($res){
            return json_encode(array('status'=>1,'data'=>'','msg'=>'成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'失败'));
        }
    }
    //快递员验收
    public function courierAcceptance(){
        $aid=I('sorting_id');
        $type=I('type',0);
        $page=I('page',1);
        //今日处理单数
       $thisCount=M('order')->where('shipping_time','gt',strtotime(date('Y-m-d 0:0:0',time())))->where('shipping_time','lt',strtotime(date('Y-m-d 23:59:59',time())))->where('sorting_id',$aid)->where('order_status',2)->count();
        //本周处理单数
        $WeekCount=M('order')->where('shipping_time','gt',strtotime(date('Y-m-d 0:0:0', strtotime("this week Monday", time()))))->where('shipping_time','lt',strtotime(date('Y-m-d 23:59:59', strtotime("this week Sunday", time()))) + 24 * 3600 - 1)->where('sorting_id',$aid)->where('order_status',2)->count();
        //本月处理单数
        $MonthCount=M('order')->where('shipping_time','gt', mktime(0, 0, 0, date('m'), 1, date('Y')))->where('shipping_time','lt',mktime(23, 59, 59, date('m'), date('t'), date('Y')))->where('order_status',2)->where('sorting_id',$aid)->count();
       if($type==0){ //未验收
           $count=M('order')->where(['deleted'=>0,'is_cancel'=>0,'order_status'=>0])->count();
           $countPage=ceil($count/8);
           $data=M('order')->where(['deleted'=>0,'is_cancel'=>0,'order_status'=>0])->order('add_time desc')->limit((($page-1)*8),($page*8))->select();
       }elseif($type==1){ //已验收
           $count=M('order')->where(['deleted'=>0,'is_cancel'=>0,'order_status'=>2])->count();
           $countPage=ceil($count/8);
           $data=M('order')->where(['deleted'=>0,'is_cancel'=>0,'order_status'=>2])->order('add_time desc')->limit((($page-1)*8),($page*8))->select();
       }elseif($type==2){  //全部
           $count=M('order')->where(['deleted'=>0,'is_cancel'=>0])->count();
           $countPage=ceil($count/8);
           $data=M('order')->where(['deleted'=>0,'is_cancel'=>0])->order('add_time desc')->limit((($page-1)*8),($page*8))->select();
       }
        foreach($data as $k=>$v){
            $data[$k]['add_time']=date('Y-m-d',$v['add_time']);
        }
        $datas['thisCount']=$thisCount;
        $datas['WeekCount']=$WeekCount;
        $datas['MonthCount']=$MonthCount;
        $datas['data']=$data;
        $datas['countPage']=$countPage;
        if($datas){
            return json_encode(array('status'=>1,'data'=>$datas,'msg'=>'获取成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'暂无数据'));
        }
    }
    //最新环保达人
    public function environmentalPeople(){
        $num=tpCache('basic.need'); //显示多少条
        $pt=tpCache('basic.app_test');//一公斤回收健减碳量
        $sql="select a.consignee,a.province,a.order_id,a.goods_conform_heavy,a.conform_price,b.head_pic from tp_order as a left join tp_users as b on a.user_id=b.user_id where a.deleted=0 and a.is_cancel=0 and a.order_status=2 group by a.user_id order by a.order_id desc limit ".$num;
        $data=M('order')->query($sql);
        foreach($data as $k=>$v){
            $data[$k]['jtl']=round($v['goods_conform_heavy']*$pt,2);
        }
        if($data){
            return json_encode(array('status'=>1,'data'=>$data,'msg'=>'获取成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'暂无数据'));
        }
    }
    //立即预约
    public function appointmentOrder(){
        $data['consignee']=I('post.consignee');
        $data['user_id']=I('post.user_id');
        $data['province']=get_region2(I('post.province'));
        $data['city']=get_region2(I('post.city'));
        $data['address']=I('post.address');
        $data['twon']=get_region2(I('post.twon'));
        $data['mobile']=I('post.mobile');
        if(!check_mobile($data['mobile'])){
            return json_encode(array('status'=>0,'msg'=>'手机号码格式错误','data'=>''));
        }
        $data['goods_heavy']=I('post.goods_heavy');
        $data['goods_price']=I('post.goods_price');
        $data['shipping_time']=strtotime(I('post.shipping_time'));
        $data['add_time']=time();
        $CartLogic = new OrderLogic();
        $data['order_sn']=$CartLogic->get_order_sn();
        $res=M('order')->add($data);
        if($res){
            return json_encode(array('status'=>1,'msg'=>'预约成功','data'=>$res));
        }else{
            return json_encode(array('status'=>0,'msg'=>'预约失败,请重试','data'=>''));
        }
    }
    //兑换商品页面
    public function exchange(){
            $data=M('goods')->where(['is_on_sale'=>1])->order('goods_id desc')->field('goods_id,original_img,goods_name,shop_price')->limit(12)->select();
            if($data){
                return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
            }else{
                return json_encode(array('status'=>0,'msg'=>'暂无商品','data'=>''));
            }
    }
    //兑换商品
    public function exchangeGoods(){
        $money=I('shop_price');
        $userPoints=M('users')->where(['user_id'=>I('user_id')])->getField('pay_points');
        if($userPoints<$money){
            return json_encode(array('status'=>0,'msg'=>'你的金币不足','data'=>''));
        }
        $data['goods_id']=I('goods_id');
        $data['goods_name']=getgoods(I('goods_id'));
        $data['user_id']=I('user_id');
        $data['goods_sn']=uniqid('LTJ');
        $data['nickname']=I('nickname');
        $data['province']=get_region2(I('post.province'));
        $data['city']=get_region2(I('post.city'));
        $data['address']=I('post.address');
        $data['twon']=get_region2(I('post.twon'));
        $data['mobile']=I('mobile');
        if(!check_mobile($data['mobile'])){
            return json_encode(array('status'=>0,'msg'=>'手机号码格式错误','data'=>''));
        }
        $data['wx']=I('wx');
        $count=M('goods')->where(['goods_id'=>$data['goods_id']])->field('store_count');
        if($count<1){
            return json_encode(array('status'=>0,'msg'=>'次粮仓已空，正在补货','data'=>''));
        }
        $res=M('order_goods')->add($data);
        if($res){
            M('users')->where(['user_id'=>$data['user_id']])->setDec('pay_points',$money);
            M('goods')->where(['goods_id'=>$data['goods_id']])->setDec('store_count');
            M('stock_log')->add(array('goods_id'=>$data['goods_id'],'goods_name'=>$data['goods_name'],'order_id'=>$res,'muid'=>$data['user_id'],'stock'=>'-1','ctime'=>time()));
            return json_encode(array('status'=>1,'msg'=>'兑换成功','data'=>''));
        }else{
            return json_encode(array('status'=>0,'msg'=>'网络错误','data'=>''));
        }
    }
//删除订单
    public function del_order()
    {
        $token=I('get.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $uid = I('get.user_id/d');
        $order_id = I('order_sn/d',0);
        $orderLogic = new OrderLogic;
        $return = $orderLogic->delOrder($order_id,$uid);
        $this->ajaxReturn($return);
    }

    /*
     * 取消订单
     */
    public function cancel_order(){
        $uid = I('get.user_id/d');
        $id = I('get.order_id');
        $order = M('order')->where(array('order_id'=>$id,'user_id'=>$uid))->find();
        if(empty($order)){
            return array('status'=>0,'msg'=>'订单不存在','data'=>'');
        }
        if($order['is_cancel'] == 1){
            return array('status'=>0,'msg'=>'该订单已取消','data'=>'');
        }
        $res=M('order')->where(['user_id'=>$uid,'order_id'=>$id])->setField('is_cancel',1);
        if($res){
            return array('status'=>1,'msg'=>'取消成功','data'=>'');
        }else{
            return array('status'=>0,'msg'=>'取消失败','data'=>'');
        }
    }

    public function cancel_order_info(){
    	$order_id = I('order_id/d',0);
    	$order = M('order')->where(array('order_id'=>$order_id,'order_status'=>3,'pay_status'=>1))->find();
    	$this->assign('order', $order);
    	return $this->fetch();
    }
    //取消订单弹窗
    public function refund_order()
    {
    	$order_id = I('get.order_id/d');

    	$order = M('order')
                ->field('order_id,pay_code,pay_name,user_money,integral_money,coupon_price,order_amount,total_amount')
                ->where(['order_id' => $order_id, 'user_id' => $this->user_id])
                ->find();

        if (!$order) {
            return $this->error('订单不存在');
        }

        $this->assign('user',  $this->user);
        $this->assign('order', $order);
    	return $this->fetch();
    }

    //申请取消订单
    public function record_refund_order()
    {
        $order_id   = input('post.order_id', 0);
        $user_note  = input('post.user_note', '');
        $consignee  = input('post.consignee', '');
        $mobile     = input('post.mobile', '');

        $logic = new OrderLogic;
        $return = $logic->recordRefundOrder($this->user_id, $order_id, $user_note, $consignee, $mobile);

        $this->ajaxReturn($return);
    }

    public function virtual_order(){
        $Order = new \app\common\model\Order();
    	$order_id = I('get.order_id/d');
    	$map['order_id'] = $order_id;
    	$map['user_id'] = $this->user_id;
    	$orderobj = $Order->where($map)->find();
        if(!$orderobj) $this->error('没有获取到订单信息');
        // 添加属性  包括按钮显示属性 和 订单状态显示属性
        $order_info = $orderobj->append(['order_status_detail','order_button','order_goods'])->toArray();
    	//获取订单操作记录
    	$order_action = M('order_action')->where(array('order_id'=>$order_id))->select();
    	$this->assign('order_status',C('ORDER_STATUS'));
    	$this->assign('pay_status',C('PAY_STATUS'));
    	$this->assign('order_info',$order_info);
    	$this->assign('order_action',$order_action);

    	if($order_info['pay_status'] == 1 && $order_info['order_status']!=3){
    		$vrorder = M('vr_order_code')->where(array('order_id'=>$order_id))->select();
    		$this->assign('vrorder',$vrorder);
    	}
    	return $this->fetch();
    }


    /*
     * 评论晒单
     */
    public function comment()
    {
        $user_id = $this->user_id;
        $status = I('get.status', -1);
        $logic = new CommentLogic;
        $data = $logic->getComment($user_id, $status); //获取评论列表
        $this->assign('page', $data['show']);// 赋值分页输出
        $this->assign('comment_page', $data['page']);
        $this->assign('comment_list', $data['result']);
        $this->assign('active', 'comment');
        return $this->fetch();
    }

    /**
     * 删除评价
     */
    public function delComment()
    {
        $token=I('get.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $uid = I('get.user_id/d');
        $comment_id = I('get.comment_id');
        if (empty($comment_id)) {
            return json_encode(array('status'=>0,'data'=>'','msg'=>'参数错误'));
        }
        $comment = Db::name('comment')->where('comment_id', $comment_id)->find();
        if ($uid != $comment['user_id']) {
            return json_encode(array('status'=>0,'data'=>'','msg'=>'不能删除别人的评论'));
        }
        $res=Db::name('comment')->where('comment_id', $comment_id)->delete();
        if($res){
            return json_encode(array('status'=>1,'data'=>'','msg'=>'删除评论成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'删除评论失败'));
        }
    }

    /**
     *  评价点赞
     * @author dyr
     */
    public function ajaxZan()
    {
        $token=I('post.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $comment_id = I('post.comment_id/d');
        if(!$comment_id){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'参数无效'));
        }
        $comment_info = M('comment')->where(array('comment_id' => $comment_id))->find();
        $comment_data['zan_num'] = $comment_info['zan_num'] + 1;
        $res=M('comment')->where(array('comment_id' => $comment_id))->save($comment_data);
            if($res){
                return json_encode(array('status'=>1,'data'=>'','msg'=>'点赞成功'));
            }else{
                return json_encode(array('status'=>0,'data'=>'','msg'=>'点赞失败'));
            }
    }

    /**
     * 添加回复评论
     * @author dyr
     */
    public function reply_add()
    {
        $token=I('post.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $comment_id = I('post.comment_id/d');
        if(!$comment_id){
            return json_encode(array('status'=>0,'msg'=>'参数错误','data'=>''));
        }
        $reply_id = I('post.reply_id/d', 0);//回复者id
        if(!$reply_id){
            return json_encode(array('status'=>0,'msg'=>'您还未登录，不能回复','data'=>''));
        }
        $content = I('post.content');     //回复内容
        if(!$content){
            return json_encode(array('status'=>0,'msg'=>'回复内容不能为空','data'=>''));
        }
       $nick=M('users')->where(['user_id'=>$reply_id])->field('nickname')->find();
        $reply_data = array(
            'parent_id' => $comment_id,
            'zan_userid' => $reply_id,
            'content' => $content,
            'username' => $nick['nickname'],
            'add_time' => time()
        );
       $res=M('comment')->add($reply_data);
        if($res){
            return json_encode(array('status'=>1,'msg'=>'回复成功','data'=>''));
        }else{
            return json_encode(array('status'=>0,'msg'=>'回复失败','data'=>''));
        }

    }
    //确认收货
    public function order_confirm()
    {
        $token=I('post.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $id = I('post.order_sn', 0);
        $user_id=I('post.user_id/d', 0);
        if(!$id || !$user_id){
            return json_encode(array('status'=>0,'msg'=>'参数错误','data'=>''));
        }
    	$data = confirm_order($id, $user_id);
        $this->ajaxReturn($data);
    }

    /**
     * 可申请退换货
     */
    public function return_goods_index(){
        $sale_t = I('sale_t/i',0);
        $keywords = I('keywords');
        $model = new OrderLogic();
        $data = $model->getReturnGoodsIndex($sale_t,$keywords,$this->user_id);
    	$this->assign('store_list',$data['store_list']);
    	$this->assign('order_list',$data['order_list']);
    	$this->assign('page',$data['show']);
    	return $this->fetch();
    }

    /**
     * 申请退货
     */
    public function return_goods()
    {
        $rec_id = I('rec_id',0);
        $return_goods = M('return_goods')->where(array('rec_id'=>$rec_id))->find();
        if(!empty($return_goods))
        {
            $this->error('已经提交过退货申请!',U('Order/return_goods_info',array('id'=>$return_goods['id'])));
        }
        $order_goods = M('order_goods')->where(array('rec_id'=>$rec_id))->find();
        $order = M('order')->where(array('order_id'=>$order_goods['order_id'],'user_id'=>$this->user_id))->find();
        $confirm_time_config = tpCache('shopping.auto_service_date');//后台设置多少天内可申请售后
        $confirm_time = $confirm_time_config * 24 * 60 * 60;
        if ((time() - $order['confirm_time']) > $confirm_time && !empty($order['confirm_time'])) {
            $this->error('已经超过' . $confirm_time_config . "天内退货时间");
//            return ['result'=>-1,'msg'=>'已经超过' . $confirm_time_config . "天内退货时间"];
        }
        if(empty($order))$this->error('非法操作');
        if(IS_POST)
        {
            $model = new OrderLogic();
            $res = $model->addReturnGoods($rec_id,$order);  //申请售后
            if($res['status']==1)$this->success($res['msg'],U('Order/return_goods_list'));
            $this->error($res['msg']);
        }
        $region_id[] = tpCache('shop_info.province');
        $region_id[] = tpCache('shop_info.city');
        $region_id[] = tpCache('shop_info.district');
        $region_id[] = 0;
        $return_address = M('region')->where("id in (".implode(',', $region_id).")")->getField('id,name');
        $order_info = array_merge($order,$order_goods);  //合并数组
        $this->assign('return_address', $return_address);
        $this->assign('goods', $order_goods);
    	$this->assign('order',$order);
        return $this->fetch();
    }

    /**
     * 退换货列表
     */
    public function return_goods_list()
    {
        $where = " user_id=$this->user_id ";
        // 搜索订单 根据商品名称 或者 订单编号
        $search_key = trim(I('search_key'));
        if($search_key)
        {
            $where .= " and order_sn=$search_key";
        }
        $count = M('return_goods')->where($where)->count();
        $page = new Page($count,10);
        $list = M('return_goods')->where($where)->order("id desc")->limit("{$page->firstRow},{$page->listRows}")->select();
        $goods_id_arr = get_arr_column($list, 'goods_id');
        if(!empty($goods_id_arr))
            $goodsList = M('goods')->where("goods_id","in", implode(',',$goods_id_arr))->getField('goods_id,goods_name');
        $state = C('REFUND_STATUS');
        $this->assign('state',$state);
        $this->assign('goodsList', $goodsList);
        $this->assign('list', $list);
        $this->assign('page', $page->show());// 赋值分页输出
        return $this->fetch();
    }

    /**
     *  退货详情
     */
    public function return_goods_info()
    {
        $id = I('id/d',0);
        $ReturnGoodsModel = new \app\common\model\ReturnGoods();
        $return_goods=$ReturnGoodsModel::get(['id' => $id,'user_id'=>$this->user_id]);
        if(empty($return_goods)) $this->error('参数错误');
        $return_goods['seller_delivery'] = unserialize($return_goods['seller_delivery']);  //订单的物流信息，服务类型为换货会显示
        if($return_goods['imgs'])
            $return_goods['imgs'] = explode(',', $return_goods['imgs']);
        $goods = M('goods')->where("goods_id", $return_goods['goods_id'])->find();
        $this->assign('state',C('REFUND_STATUS'));
        $this->assign('goods',$goods);
        $this->assign('return_goods',$return_goods);
        return $this->fetch();
    }

    public function return_goods_refund()
    {
    	$order_sn = I('order_sn');
    	$where = array('user_id'=>$this->user_id);
    	if($order_sn){
    		$where['order_sn'] = $order_sn;
    	}
    	$where['status'] = 5;
    	$count = M('return_goods')->where($where)->count();
    	$page = new Page($count,10);
    	$list = M('return_goods')->where($where)->order("id desc")->limit($page->firstRow, $page->listRows)->select();
    	$goods_id_arr = get_arr_column($list, 'goods_id');
    	if(!empty($goods_id_arr))
    		$goodsList = M('goods')->where("goods_id in (".  implode(',',$goods_id_arr).")")->getField('goods_id,goods_name');
    	$this->assign('goodsList', $goodsList);
    	$state = C('REFUND_STATUS');
    	$this->assign('list', $list);
    	$this->assign('state',$state);
    	$this->assign('page', $page->show());// 赋值分页输出
    	return $this->fetch();
    }

    /**
     * 取消服务单
     */
    public function return_goods_cancel(){
    	$id = I('id/d',0);
    	if(empty($id))$this->ajaxReturn(['status'=>0,'msg'=>'参数错误']);
    	$res=M('return_goods')->where(['id'=>$id,'user_id'=>$this->user_id])->save(array('status'=>-2,'canceltime'=>time()));
        if($res){
            $this->ajaxReturn(['status'=>1,'msg'=>'成功取消服务单','url'=>U('Order/return_goods_info',['id'=>$id])]);
        }
            $this->ajaxReturn(['status'=>0,'msg'=>'服务单不存在']);
    }

    /**
     * 换货商品确认收货
     * @author lxl
     * @time  17-4-25
     * */
    public function receiveConfirm(){
        $return_id=I('return_id/d');
        $return_info=M('return_goods')->field('order_id,order_sn,goods_id,spec_key')->where('id',$return_id)->find(); //查找退换货商品信息
        $update = M('return_goods')->where('id',$return_id)->save(['status'=>3]);  //要更新状态为已完成
        if($update) {
            M('order_goods')->where(array(
                'order_id' => $return_info['order_id'],
                'goods_id' => $return_info['goods_id'],
                'spec_key' => $return_info['spec_key']))->save(['is_send' => 2]);  //订单商品改为已换货
            $this->success("操作成功", U("User/return_goods_info", array('id' => $return_id)));
        }
        $this->error("操作失败");
    }

    public function lower_list(){
    	$level = I('get.level',1);
    	$q = I('post.q','','trim');
    	$condition = array(1=>'first_leader',2=>'second_leader',3=>'third_leader');
    	$where = "{$condition[$level]} = {$this->user_id}";
    	$q && $where .= " and (nickname like '%$q%' or user_id = '$q' or mobile = '$q')";

    	$count = M('users')->where($where)->count();
    	$page = new Page($count,10);
    	$list = M('users')->where($where)->limit("{$page->firstRow},{$page->listRows}")->order('user_id desc')->select();

    	$this->assign('count', $count);// 总人数
    	$this->assign('level',$level);
    	$this->assign('page', $page->show());// 赋值分页输出
    	$this->assign('member',$list); // 下线
    	return $this->fetch();
    }

    public function income(){
    	$result = Db::query("select sum(goods_price) as goods_price, sum(money) as money from __PREFIX__rebate_log where user_id = {$this->user_id}");
    	$result = $result[0];
    	$result['goods_price'] = $result['goods_price'] ? $result['goods_price'] : 0;
    	$result['money'] = $result['money'] ? $result['money'] : 0;
    	$status = I('get.status',-2);

    	if($status=='0' || $status>0){
    		$condition['status'] = $status;
    	}

    	$condition['user_id'] = $this->user_id;
    	$count = M('rebate_log')->where($condition)->count();
    	$page = new Page($count,10);
    	$rebate_log = M('rebate_log')->where($condition)->limit("{$page->firstRow},{$page->listRows}")->order('user_id desc')->select();
    	$this->assign('page', $page->show());// 赋值分页输出
    	$this->assign('rebate_log',$rebate_log);
    	$this->assign('status',$status);
    	$this->assign('result',$result);
    	return $this->fetch();
    }

    /**
     * 订单商品评价列表
     */
    public function comment_list()
    {
        $order_id = I('order_id/d');
        $rec_id = I('rec_id/d');
        if (empty($order_id) || empty($rec_id)) {
            $this->error("参数错误");
        } else {
            //查找订单
            $order_comment_where['order_id'] = $order_id;
            $order_info = M('order')->field('order_sn,order_id,add_time,order_prom_type') ->where($order_comment_where)->find();
            //查找评价商品
            $order_comment_where['rec_id'] = $rec_id;
            $order_goods = M('order_goods')
                ->field('goods_id,is_comment,goods_name,goods_num,goods_price,spec_key_name')
                ->where($order_comment_where)
                ->find();
            $order_info = array_merge($order_info,$order_goods);
            $this->assign('order_info', $order_info);
            return $this->fetch();
        }
    }

    /*
    *添加评论
    */
    public function add_comment()
    {
        $token=I('token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $add['goods_id'] = I('goods_id/d');
        $add['user_id'] = I('user_id');
        $add['order_id'] = I('order_id/d');
        if(!$add['goods_id'] || !$add['user_id']){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'参数无效'));
        }
         $data=M('users')->where(['user_id'=>I('user_id')])->field('email,nickname')->find();
        $add['email'] = $data['email'];
        $add['username'] = $data['nickname'];
        $add['content'] = I('content');
        $add['add_time'] = time();
        $add['parent_id'] = 0;
        $add['is_anonymous'] = I('hide_username',0); //是否匿名评价:0不是\1是
        $add['service_rank'] = I('service_rank');    //商家
        $add['deliver_rank'] = I('deliver_rank');   //物流
        $add['goods_rank'] = I('goods_rank');        //商品
        $add['is_show'] = 1; //默认显示
        $add['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $file = request()->file('comment_img');
        if(!empty($file) || isset($file)){
            $info=$this->upload($file);
            $add['img'] = serialize($info);

        }
        $logic = new UsersLogic();
        //添加评论
        $row = $logic->adds_comment($add);
        exit(json_encode($row));
    }
    public function upload($file){
        if($file){
            foreach($file as $k=>$files){
                $info = $file[$k]->move('public/upload/qr_code');
                if($info){
                    return $this->request->domain().'/public/upload/qr_code/'.$info->getSaveName();
                }else{
                    echo $file->getError();
                }
            }

        }
    }
    //订单列表
    public function orderList()
    {
//        $token=I('get.token');
//        if($token !=self::CHECK_TOKEN){
//            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
//        }
        $type=I('get.type/d',0);
        $uid=I('get.user_id');
        if(!$uid){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'参数无效'));
        }
        if($type==0){            //全部订单
            $order="select a.order_id,a.order_sn,a.user_id,a.goods_id,a.order_status,b.goods_name,b.original_img,a.goods_price,a.goods_num,a.shipping_price,a.pay_status,a.shipping_status,a.spec_key,a.spec_key_name from tp_order as a left join tp_goods as b on a.goods_id=b.goods_id WHERE a.user_id=".$uid." and a.deleted=0";
        }elseif($type==1){       //待付款订单
            $order="select a.order_id,a.order_sn,a.user_id,a.goods_id, a.order_status,b.goods_name,b.original_img,a.goods_price,a.goods_num,a.shipping_price,a.pay_status,a.shipping_status,a.spec_key,a.spec_key_name from tp_order as a left join tp_goods as b on a.goods_id=b.goods_id WHERE a.user_id=".$uid." and a.deleted=0 and a.pay_status=0 and order_status=1 and shipping_status=0";
        }elseif($type==2){       //待发货订单
            $order="select a.order_id,a.order_sn,a.user_id,a.goods_id,a.order_status,b.goods_name,b.original_img,a.goods_price,a.goods_num,a.shipping_price,a.pay_status,a.shipping_status,a.spec_key,a.spec_key_name from tp_order as a left join tp_goods as b on a.goods_id=b.goods_id WHERE a.user_id=".$uid." and a.deleted=0 and a.pay_status=1 and order_status=2 and shipping_status=0";
        }elseif($type==3){       //待收货订单
            $order="select a.order_id,a.order_sn,a.user_id,a.goods_id,a.order_status,b.goods_name,b.original_img,a.goods_price,a.goods_num,a.shipping_price,a.pay_status,a.shipping_status,a.spec_key,a.spec_key_name from tp_order as a left join tp_goods as b on a.goods_id=b.goods_id WHERE a.user_id=".$uid." and a.deleted=0 and a.pay_status=1 and order_status=3 and shipping_status=1";
        }elseif($type==4){       //待评价订单
            $order="select a.order_id,a.order_sn,a.user_id,a.goods_id,a.order_status,b.goods_name,b.original_img,a.goods_price,a.goods_num,a.shipping_price,a.pay_status,a.shipping_status,a.spec_key,a.spec_key_name from tp_order as a left join tp_goods as b on a.goods_id=b.goods_id WHERE a.user_id=".$uid." and a.deleted=0 and a.pay_status=1 and order_status=4 and shipping_status=2";
        }else{//已完成
            $order="select a.order_id,a.order_sn,a.user_id,a.goods_id,a.order_status,b.goods_name,b.original_img,a.goods_price,a.goods_num,a.shipping_price,a.pay_status,a.shipping_status,a.spec_key,a.spec_key_name from tp_order as a left join tp_goods as b on a.goods_id=b.goods_id WHERE a.user_id=".$uid." and a.deleted=0 and a.pay_status=1 and order_status=5 and shipping_status=2";
        }
        $order=M('order')->query($order);
        $wl=M('delivery_doc')->where(['user_id'=>$uid])->field('order_sn,invoice_no')->select();
        foreach($order as $k=>$v){
            $order[$k]['original_img']='http://'.$_SERVER['HTTP_HOST'].$v['original_img'];
            foreach($wl as $k1=>$v1){
                if($order[$k]['order_sn']==$wl[$k1]['order_sn']){
                    $order[$k]['invoice_no']=$wl[$k1]['invoice_no'];
                }
            }
        }
        if($order){
            return json_encode(array('status'=>1,'data'=>$order,'msg'=>'获取成功'));
        }else{
            $data=[];
            return json_encode(array('status'=>0,'data'=>$data,'msg'=>'没有订单'));
        }
    }
    public function first(){
        if(IS_POST){
            $file=request()->file('img');
            $info=$this->upload($file);
            dump($info);die;
        }
        return $this->fetch();
    }

    /**
     * 结算页
     * @return mixed
     */
    public function order()
    {
        $order_id = I('order_id/d',0);
        $address_id = I('address_id/d');
        $uid=I('user_id/d',0);
        if(empty($uid)){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'请先登录'));
        }

        $order = M('order')->where(['order_id'=>$order_id,'user_id'=>$uid])->find();
        if(empty($order)){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'订单不存在或者已取消'));
        }
        if ($address_id) {
            $address_where = ['address_id' => $address_id];
        } else {
            $address_where = ["user_id" => $uid];
        }
        $address = Db::name('user_address')->where($address_where)->order(['is_default'=>'desc'])->find();
        if(empty($address)){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'请输入收货地址'));
        }
        $order_goods = M('order')->with('goods')->where(['order_id' => $order_id])->find();
        // 如果已经支付过的订单直接到订单详情页面. 不再进入支付页面
        if($order['pay_status'] == 1){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'该订单已支付'));
        }
        if($order['order_status'] == 0 ){   //订单已经取消
            return json_encode(array('status'=>0,'data'=>'','msg'=>'该订单已取消'));
        }
        //微信浏览器
        if(strstr($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')){
            $plugin_where = ['type'=>'payment','status'=>1,'code'=>'weixin'];
        }else{
            $plugin_where = ['type'=>'payment','status'=>1,'scene'=>1];
        }
        $pluginList = Db::name('plugin')->where($plugin_where)->select();
        $paymentList = convert_arr_key($pluginList, 'code');
        //不支持货到付款
        foreach ($paymentList as $key => $val) {
            $val['config_value'] = unserialize($val['config_value']);
            //判断当前浏览器显示支付方式
            if (($key == 'weixin' && !is_weixin()) || ($key == 'alipayMobile' && is_weixin())) {
                unset($paymentList[$key]);
            }
        }
        $ShippingArea = new ShippingArea();
        $shipping_area= $ShippingArea->alias('sa')->join('__PLUGIN__ p','sa.shipping_code = p.code')->with('plugin')
            ->where(['sa.is_default' => 1,'p.type'=>'shipping','p.status'=>1])->group("sa.shipping_code")->cache(true, TPSHOP_CACHE_TIME)->select();
        //订单没有使用过优惠券
        if($order['coupon_price'] <= 0){
            $couponLogic = new CouponLogic();
            $TeamOrderLogic = new TeamOrderLogic();
            $userCouponList = $couponLogic->getUserAbleCouponList($this->user_id, [$order_goods['goods_id']], [$order_goods['goods']['cat_id']]);//用户可用的优惠券列表
            $TeamOrderLogic->setOrder($order);
            $userCartCouponList = $TeamOrderLogic->getCouponOrderList($userCouponList);
            $this->assign('userCartCouponList', $userCartCouponList);
        }
        $this->assign('paymentList', $paymentList);
        $this->assign('order', $order);
        $this->assign('order_goods', $order_goods);
        $this->assign('shipping_area',$shipping_area);
        return $this->fetch();
    }


}