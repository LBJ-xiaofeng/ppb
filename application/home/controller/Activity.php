<?php
namespace app\home\controller;

use app\common\logic\GoodsActivityLogic;
use app\common\logic\ActivityLogic;
use app\common\logic\GoodsLogic;
use app\common\model\FlashSale;
use app\common\model\GroupBuy;
use app\common\logic\OrderLogic;
use think\AjaxPage;
use think\Controller;
use think\Page;
use think\Db;

class Activity extends Base
{
    /**
     * 团购活动列表
     */
    public function group_list()
    {
        $GroupBuy = new GroupBuy();
        $where = array(
            'gb.start_time'        =>array('elt',time()),
            'gb.end_time'          =>array('egt',time()),
            'gb.is_end'            =>0,
            'g.is_on_sale'         =>1
        );

        $list = $GroupBuy
            ->alias('gb')
            ->with(['goods','specGoodsPrice'])
            ->join('__GOODS__ g', 'g.goods_id = gb.goods_id')
            ->where($where)
            ->select();
        print_r($list);die;
    }

    /**
     * 预售列表页
     */
    public function pre_sell_list()
    {
        $goodsActivityLogic = new GoodsActivityLogic();
        $pre_sell_list = Db::name('goods_activity')->where(array('act_type' => 1, 'is_finished' => 0))->select();
        foreach ($pre_sell_list as $key => $val) {
            $pre_sell_list[$key] = array_merge($pre_sell_list[$key], unserialize($pre_sell_list[$key]['ext_info']));
            $pre_sell_list[$key]['act_status'] = $goodsActivityLogic->getPreStatusAttr($pre_sell_list[$key]);
            $pre_count_info = $goodsActivityLogic->getPreCountInfo($pre_sell_list[$key]['act_id'], $pre_sell_list[$key]['goods_id']);
            $pre_sell_list[$key] = array_merge($pre_sell_list[$key], $pre_count_info);
            $pre_sell_list[$key]['price'] = $goodsActivityLogic->getPrePrice($pre_sell_list[$key]['total_goods'], $pre_sell_list[$key]['price_ladder']);
        }
        $this->assign('pre_sell_list', $pre_sell_list);
        return $this->fetch();
    }

    /**
     *   预售详情页
     */
    public function pre_sell()
    {
        $id = I('id/d', 0);
        $pre_sell_info = M('goods_activity')->where(array('act_id' => $id, 'act_type' => 1))->find();
        if (empty($pre_sell_info)) {
            $this->error('对不起，该预售商品不存在或者已经下架了', U('Home/Activity/pre_sell_list'));
            exit();
        }
        $goods = M('goods')->where(array('goods_id' => $pre_sell_info['goods_id']))->find();
        if (empty($goods)) {
            $this->error('对不起，该预售商品不存在或者已经下架了', U('Home/Activity/pre_sell_list'));
            exit();
        }
        $pre_sell_info = array_merge($pre_sell_info, unserialize($pre_sell_info['ext_info']));
        $goodsActivityLogic = new GoodsActivityLogic();
        $pre_count_info = $goodsActivityLogic->getPreCountInfo($pre_sell_info['act_id'], $pre_sell_info['goods_id']);//预售商品的订购数量和订单数量
        $pre_sell_info['price'] = $goodsActivityLogic->getPrePrice($pre_count_info['total_goods'], $pre_sell_info['price_ladder']);//预售商品价格
        $pre_sell_info['amount'] = $goodsActivityLogic->getPreAmount($pre_count_info['total_goods'], $pre_sell_info['price_ladder']);//预售商品数额ing
        if ($goods['brand_id']) {
            $brand = M('brand')->where(array('id' => $goods['brand_id']))->find();
            $goods['brand_name'] = $brand['name'];
        }
        $goods_images_list = M('GoodsImages')->where(array('goods_id' => $goods['goods_id']))->select(); // 商品 图册
        $goods_attribute = M('GoodsAttribute')->getField('attr_id,attr_name'); // 查询属性
        $goods_attr_list = M('GoodsAttr')->where(array('goods_id' => $goods['goods_id']))->select(); // 查询商品属性表
        $goodsLogic = new GoodsLogic();
        $commentStatistics = $goodsLogic->commentStatistics($goods['goods_id']);// 获取某个商品的评论统计
        $this->assign('pre_count_info', $pre_count_info);//预售商品的订购数量和订单数量
        $this->assign('commentStatistics', $commentStatistics);//评论概览
        $this->assign('goods_attribute', $goods_attribute);//属性值
        $this->assign('goods_attr_list', $goods_attr_list);//属性列表
        $this->assign('goods_images_list', $goods_images_list);//商品缩略图
        $this->assign('pre_sell_info', $pre_sell_info);
        $this->assign('look_see',$goodsLogic->get_look_see($goods));//看了又看
        $this->assign('goods', $goods);
        return $this->fetch();
    }



    // 团购促销产品
    public function promoteList()
    {
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $sql="select a.id, a.expression,a.start_time,a.end_time,a.is_end,a.goods_num,a.goods_id,a.buy_num,b.goods_name,b.shop_price,b.cat_id,b.click_count,b.original_img,b.goods_type,b.sales_sum from tp_prom_goods as a left join tp_goods as b on a.goods_id=b.goods_id where a.end_time>".time()." and a.type=1 and b.is_on_sale=1";
        $data=M('prom_goods')->query($sql);

        foreach($data as $k=>$v){
            $idst=M('prom_list')->where(['status'=>1,'prom_id'=>$v['id']])->field('id')->select();
            if(!isset($idst) || empty($idst)){
                $tnumber=0;
            }else{
                foreach ($idst as $key => $value) {
                    $ids[]=$value['id'];
                }
                $idst=implode(',',$ids);
                $where['order_prom_id']=array('in',$idst);
                $tnumber=M('order')->where($where)->where(['pay_status'=>1])->count();
                // print_r($ids);die;
            }
            $data[$k]['tnumber']=$tnumber;
            $data[$k]['prom_price']=round($v['shop_price']*$v['expression']/100,2);
            $data[$k]['start_time']=date('Y-m-d H:i',$v['start_time']);
            $data[$k]['end_time']=date('Y-m-d H:i',$v['end_time']);
            $data[$k]['original_img']='http://'.$_SERVER['HTTP_HOST'].$v['original_img'];
        }


        if($data){
            return json_encode(array('status'=>1,'data'=>$data,'msg'=>'获取活动产品成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'暂无活动，敬请期待'));
        }
    }
//    发起团购
    public function clickPromlist(){
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        header("Content-type: text/html; charset=utf-8");
        $prom_id = I("get.prom_id/d");
        $user_id=I('get.user_id');
        $attr_id=I('get.attr_id',0);
        $attr=M('goods_attr')->where(['goods_attr_id'=>$attr_id])->field('attr_name,attr_price,attr_value')->find();
        $sql="select a.expression,a.end_time,a.is_end,a.goods_num,a.goods_id,a.buy_num,b.goods_name,b.shop_price,b.cat_id from tp_prom_goods as a left join tp_goods as b on a.goods_id=b.goods_id where a.end_time>".time()." and a.type=1 and b.is_on_sale=1 and a.id=".$prom_id." limit 1";
        $data=M('prom_goods')->query($sql);
        foreach($data as $k=>$v){
            $datas=$v;
        }
        $datad['user_id']=$user_id;
        $datad['prom_id']=$prom_id;
        $datad['goods_id']=$datas['goods_id'];
        $datad['expression']=$datas['expression'];
        $datad['is_end']=$datas['is_end'];
        $datad['goods_name']=$datas['goods_name'];
        $datad['shop_price']=$attr['attr_price']*$datas['expression']/100;
        $datad['attr_id']=$attr_id;
        $datad['attr_name']=$attr['attr_name'];
        $datad['attr_value']=$attr['attr_value'];
        $datad['add_time']=time();
        $datad['status']=0 ;
        $res=M('prom_list')->add($datad);
        if($res){
            return json_encode(array('status'=>1,'，msg'=>'前往支付页面','data'=>$res));
        }else{
            return json_encode(array('status'=>0,'，msg'=>'网络错误','data'=>''));
        }
    }
    //下单支付页面
    public function Promlistpay(){
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $id=I('list_id');//该团id
        $uid=I('user_id');//客户id
        $data=M('prom_list')->where(['id'=>$id])->find();
        if(!$id){
            return json_encode(array('status'=>0,'msg'=>'参数错误','data'=>''));
        }
        $address=M('user_address')->where(['user_id'=>$uid,'is_default'=>1])->field('address_id,consignee,mobile,province,city,twon,address')->find();
        $CartLogic = new OrderLogic();
        if($address){
            $address['province']=$CartLogic->getdress($address['province']);
            $address['city']=$CartLogic->getdress($address['city']);
            $address['twon']=$CartLogic->getdress($address['twon']);   //获取默认收货地址
        }
        $goods=M('goods')->where(['goods_id'=>$data['goods_id']])->field('original_img,is_free_shipping')->find();
        $data['img']='http://'.$_SERVER['HTTP_HOST'].$goods['original_img'];
        if($goods['is_free_shipping']==1){
            $data['is_free_shipping']=0;
        }
        //积分规则
        $point=M('config')->where(['name'=>'order_point'])->find();
        $give_integral = ceil($data['shop_price']/50)*$point['value'];
        $data['give_integral']=$give_integral;
        $data['attr_value']=$data['attr_name'].':'.$data['attr_value'];
        if($address){
            $datas['address']=$address;
        }else{
            $datas['address']='';
        }
        $datas['ordergoods']=$data;

        if(!empty($datas)){
            return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$datas));
        }else{
            return json_encode(array('status'=>0,'msg'=>'网络错误，请重试','data'=>''));
        }
    }
    //点击支付
    public function listorder(){
        $id=I('list_id');//该团id
        $uid=I('user_id');//客户id
        $address_id=I('address_id');//地址id
        $pay_type=I('pay_type');//支付方式
        $data=M('prom_list')->where(['id'=>$id])->find();//商品资料
        //积分规则
        $point=M('config')->where(['name'=>'order_point'])->find();
        $give_integral = ceil($data['shop_price']/50)*$point['value'];
        $address=M('user_address')->where(['address_id'=>$address_id])->field('address_id,consignee,mobile,province,city,twon,address')->find();
        $CartLogic = new OrderLogic();
        if($address){
            $address['province']=$CartLogic->getdress($address['province']);
            $address['city']=$CartLogic->getdress($address['city']);
            $address['twon']=$CartLogic->getdress($address['twon']);   //获取默认收货地址
        }
        $CartLogic = new OrderLogic();
        $order_sn=$CartLogic->get_order_sn();
        $orderArr = array('order_sn'=>$order_sn,'goods_num'=>1,'goods_id'=>$data['goods_id'],'user_id'=>$uid,'order_status'=>1,'pay_status'=>0,'shipping_status'=>0,'consignee'=>$address['consignee'],'mobile'=>$address['mobile'],'province'=>$address['province'],'city'=>$address['city'],'twon'=>$address['twon'],'address'=>$address['address'],
            'goods_price'=>$data['shop_price'],'shipping_price'=>0,'add_time'=>time(), 'order_prom_type'=>2,'order_amount'=>$data['shop_price'],'total_amount'=>$data['shop_price'],'spec_key'=> $data['attr_id'],'spec_key_name'=> $data['attr_name'].':'.$data['attr_value'],'deleted'=>0,'addtime'=>time(),'order_prom_id'=>$id
        );
        $order_id = M('order')->add($orderArr);
        if(!$order_id){
            return json_encode(array('status'=>0,'msg'=>'网络错误','data'=>''));
        }
        $data2['order_id']           = $order_id; // 订单id
        $data2['user_id']           = $uid; // 客户id
        $data2['goods_id']           = $data['goods_id']; // 商品id
        $data2['goods_name']         = $data['goods_name']; // 商品名称
        $data2['goods_sn']           = $order_sn; // 商品货号
        $data2['goods_num']          = 1; // 购买数量
        $data2['market_price']       = $data['shop_price']; // 市场价
        $data2['goods_price']        = $data['shop_price']; // 商品价
        $data2['spec_key']           = $data['attr_id']; // 商品规格id
        $data2['spec_key_name']      = $data['attr_name'].':'.$data['attr_value']; // 商品规格名称
        $data2['member_goods_price'] = $data['shop_price']; // 会员折扣价
        $data2['cost_price']         = $data['shop_price']; // 成本价
        $data2['give_integral']      = $give_integral; // 购买商品赠送积分
        $data2['prom_type']          = 2; // 0 普通订单,1 限时抢购, 2 团购 , 3 促销优惠
        $data2['prom_id']          = $id; // 团id

        $order_goods_id = M("order_goods")->add($data2);
        if(!$order_goods_id){
            return json_encode(array('status'=>0,'msg'=>'网络错误','data'=>''));
        }else{
            $pay=new Pay();
            $pay->pay_order($order_sn,$uid,$pay_type,$data['shop_price'],2);
        }
    }
    //参团
    public function addPromlist(){
        header("Content-type: text/html; charset=utf-8");
        $user_id=I('user_id');
        $id=I('list_id');
        $useid=M('prom_list')->where(['id'=>$id])->field('user_id,is_end')->find();
        $data=explode(',',$useid['user_id']);
        if(in_array($user_id,$data)){
            return json_encode(array('status'=>0,'，msg'=>'您已参与此团购','data'=>''));
        }
        if(count($data)==$useid['is_end']){
            return json_encode(array('status'=>0,'，msg'=>'此团已满','data'=>''));
        }
        return json_encode(array('status'=>1,'，msg'=>'前往支付','data'=>$id));
    }
    //团购促销商品详情
    public function promListDetail(){
        header("Content-type: text/html; charset=utf-8");
        $goodsLogic = new GoodsLogic();
        $prom_id = I("get.prom_id/d");
        $user_id=I('get.user_id');
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        //活动对应的商品
        $prom_goods=M('prom_goods')->where(['id'=>$prom_id,'type'=>1])->field('id,expression,end_time,goods_num,goods_id,buy_num')->find();
        $goods_id = $prom_goods['goods_id'];
        $Goods = new \app\common\model\Goods();
        $goods = $Goods::get($goods_id);
        if ($user_id) {
            $goodsLogic->add_visit_log($user_id, $goods);
        }
        $promList=M('prom_list')->where(['prom_id'=>$prom_id,'status'=>1])->select();
        foreach($promList as $k=>$v){
            $ids[]=$v['id'];
            $datas=explode(',',$v['user_id']);
            $name = M('users')->where(['user_id' => $datas[0]])->field('nickname,head_pic')->find();
            $promList[$k]['name']=$name['nickname'];
            $promList[$k]['pic']=$name['head_pic'];
            $avg=$v['is_end']-count($datas);
            if($avg==0){
                $promList[$k]['list']='已满';
            }else{
                $promList[$k]['list']=$avg;
            }
            $promList[$k]['count']=count($datas);
        }
// print_r($ids);die;
        if(!isset($ids) || empty($ids)){
            $tnumber=0;
        }else{
            $idst=implode(',',$ids);
            $where['order_prom_id']=array('in',$idst);
            $tnumber=M('order')->where($where)->where(['pay_status'=>1])->count();
        }

        if($goods['brand_id']){
            $goods['brand_name'] = M('brand')->where("id",$goods['brand_id'])->getField('name');
        }
        $goods_images_list = M('GoodsImages')->where("goods_id", $goods_id)->select(); // 商品 图册
        foreach($goods_images_list as $k=>$v){
            $goods_images_list[$k]['image_url']='http://'.$_SERVER['HTTP_HOST'].$v['image_url'];
        }
        $goods_type=M('goods_type')->where(['goods_id'=>$goods_id])->field('id')->find();
        $goods_attr=M('goods_attr')->where(['goods_type_id'=>$goods_type['id']])->select();//商品属性
        foreach($goods_attr as $k=>$v){
            $goods_attr[$k]['goods_price']=$prom_goods['expression']*$v['attr_price']/100;
        }
        $goods_attribute = M('goods_attribute')->where(['type_id'=>$goods_type['id']])->getField('attr_id,attr_name,attr_values'); // 查询规格
        M('Goods')->where("goods_id", $goods_id)->save(array('click_count'=>$goods['click_count']+1 )); //统计点击数
        $commentStatistics = $goodsLogic->commentStatistics($goods_id);// 获取某个商品的评论统计


        $point_rate = tpCache('shopping.point_rate');
        $coupon=$goodsLogic->getCoupon($goods_id);//获取商品优惠券
        $data['coupon']=$coupon;//商品优惠券
        $data['prom_list']=$promList;//商品团
        $data['commentStatistics']=$commentStatistics;//评论概览
        $data['goods_attribute']=$goods_attribute;//规格
        $data['goods_attr_list']=$goods_attr;//属性列表 价格 库存
        $data['goods_images_list']=$goods_images_list;//商品缩略图
        $data['siblings_cate']=$goodsLogic->get_siblings_cate($goods['cat_id']);//相关分类
        $look=$goodsLogic->get_look_see($goods);//看了又看
        foreach($look as $k=>$v){
            $look[$k]['goods_content']=html_entity_decode($v['goods_content']);
            $look[$k]['original_img']='http://'.$_SERVER['HTTP_HOST'].$v['original_img'];
        }
        $data['look_see']=$look;
        $datas=$goods->toArray();
        $datas['goods_price']=$prom_goods['expression']*$datas['shop_price']/100;
        $datas['goods_expression']=$prom_goods['expression']/10;
        $datas['goods_prom_id']=$prom_goods['id'];
        $datas['prom_num']=$prom_goods['goods_num'];
        $datas['tnumber']=$tnumber;
        $time=$prom_goods['end_time']-time();
        $datas['goods_end_time']=$time*1000;
        $day=intval($time/86400);//天数
        $fen=$time%86400;  //分钟
        $hours=intval($fen/3600);
        $min=$fen%3600;
        $mins=intval($min/60);
        $miao=$min%60;
        $datas['intval_number']=array('0'=>$day,'1'=>$hours,'2'=>$mins,'3'=>$miao);
        $datas['goods_content']=html_entity_decode($datas['goods_content']);
        $datas['original_img']='http://'.$_SERVER['HTTP_HOST'].$datas['original_img'];
        $data['goods']=$datas;
        $data['point_rate']=$point_rate;
        // print_r($data);die;
        if($data){
            return json_encode(array('status'=>1,'，msg'=>true,'data'=>$data));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'数据飞了'));
        }
    }

    // 限时抢购产品
    public function Flashsale()
    {
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $sql="select a.id, a.expression,a.start_time,a.end_time,a.is_end,a.goods_num,a.goods_price,a.goods_id,b.goods_name,b.cat_id,b.click_count,b.original_img,b.goods_type,b.sales_sum from tp_prom_goods as a left join tp_goods as b on a.goods_id=b.goods_id where a.end_time>".time()." and a.type=0 and b.is_on_sale=1 order by a.start_time asc";
        $data=M('prom_goods')->query($sql);
        foreach($data as $k=>$v){
            $data[$k]['start_time']=date('Y-m-d H:i',$v['start_time']);
            $data[$k]['end_time']=date('Y-m-d H:i',$v['end_time']);
            $data[$k]['original_img']='http://'.$_SERVER['HTTP_HOST'].$v['original_img'];
        }
        if($data){
            return json_encode(array('status'=>1,'data'=>$data,'msg'=>'获取抢购产品成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'暂无抢购产品，敬请期待'));
        }
    }
    //抢购商品详情
    public function FlashDetail(){
        header("Content-type: text/html; charset=utf-8");
        $goodsLogic = new GoodsLogic();
        $prom_id = I("get.prom_id/d");//抢购id

        $user_id=I('get.user_id'); //客户id
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        //对应的商品
        $prom_goods=M('prom_goods')->where(['id'=>$prom_id,'type'=>0])->field('id,end_time,goods_num,goods_price,goods_id,buy_num')->find();
        $goods_id = $prom_goods['goods_id'];
        $Goods = new \app\common\model\Goods();
        $goods = $Goods::get($goods_id);
        $datas=$goods->toArray();
        if ($user_id) {
            $goodsLogic->add_visit_log($user_id, $goods);
        }
        if($goods['brand_id']){
            $goods['brand_name'] = M('brand')->where("id",$goods['brand_id'])->getField('name');
        }
        $goods_images_list = M('GoodsImages')->where("goods_id", $goods_id)->select(); // 商品 图册
        foreach($goods_images_list as $k=>$v){
            $goods_images_list[$k]['image_url']='http://'.$_SERVER['HTTP_HOST'].$v['image_url'];
        }
        $avg=$goods['shop_price']-$prom_goods['goods_price'];
        $goods_type=M('goods_type')->where(['goods_id'=>$goods_id])->field('id')->find();
        $goods_attr=M('goods_attr')->where(['goods_type_id'=>$goods_type['id']])->select();//商品属性
        foreach($goods_attr as $k=>$v){
            $goods_attr[$k]['goods_price']=$v['attr_price']-$avg;
        }
        $goods_attribute = M('goods_attribute')->where(['type_id'=>$goods_type['id']])->getField('attr_id,attr_name,attr_values'); // 查询规格
        M('Goods')->where("goods_id", $goods_id)->save(array('click_count'=>$goods['click_count']+1 )); //统计点击数
        $commentStatistics = $goodsLogic->commentStatistics($goods_id);// 获取某个商品的评论统计
        $point_rate = tpCache('shopping.point_rate');
        $coupon=$goodsLogic->getCoupon($goods_id);//获取商品优惠券
        $data['coupon']=$coupon;//商品优惠券
        $data['commentStatistics']=$commentStatistics;//评论概览
        $data['goods_attribute']=$goods_attribute;//规格 价格 库存
        $data['goods_attr_list']=$goods_attr;//属性列表
        $data['goods_images_list']=$goods_images_list;//商品缩略图
        $data['siblings_cate']=$goodsLogic->get_siblings_cate($goods['cat_id']);//相关分类
        $look=$goodsLogic->get_look_see($goods);//看了又看
        foreach($look as $k=>$v){
            $look[$k]['goods_content']=html_entity_decode($v['goods_content']);
            $look[$k]['original_img']='http://'.$_SERVER['HTTP_HOST'].$v['original_img'];
        }
        $data['look_see']=$look;

        $datas['goods_price']=$prom_goods['goods_price'];
        $datas['goods_prom_id']=$prom_goods['id'];
        $datas['prom_num']=$prom_goods['goods_num'];
        $datas['buy_num']=$prom_goods['buy_num'];
        $time=$prom_goods['end_time']-time();
        $datas['goods_end_time']=$time;
        $day=intval($time/86400);//天数
        $fen=$time%86400;
        $hours=intval($fen/3600);//小时
        $min=$fen%3600;
        $mins=intval($min/60); //分钟
        $miao=$min%60;  //秒
        $datas['intval_number']=array('0'=>$day,'1'=>$hours,'2'=>$mins,'3'=>$miao);
        $datas['goods_content']=html_entity_decode($datas['goods_content']);
        $datas['original_img']='http://'.$_SERVER['HTTP_HOST'].$datas['original_img'];
        $data['goods']=$datas;
        $data['point_rate']=$point_rate;
        if($data){
            return json_encode(array('status'=>1,'，msg'=>true,'data'=>$data));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'数据飞了'));
        }
    }
    //可自领取优惠券列表
    public function getCoupons()
    {
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $data=M('coupon')->where('type=2 and send_start_time<'.time().' and send_end_time>'.time().' and status=1')->order('add_time desc')->select();
        foreach($data as $k=>$v){
            $data[$k]['send_start_time']=date('Y-m-d H:i',$v['send_start_time']);
            $data[$k]['send_end_time']=date('Y-m-d H:i',$v['send_end_time']);
            $data[$k]['use_start_time']=date('Y-m-d H:i',$v['use_start_time']);
            $data[$k]['use_end_time']=date('Y-m-d H:i',$v['use_end_time']);
            $data[$k]['add_time']=date('Y-m-d H:i',$v['add_time']);
        }
        if($data){
            return json_encode(array('status'=>1,'data'=>$data,'msg'=>'获取优惠券成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'暂无可领取优惠券'));
        }
    }
    //领取优惠券
    public function setCoupons()
    {
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $id=I('get.id');
        $uid=I('get.user_id');
        if(!$uid || !$id){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'无效参数'));
        }
        $remain=M('coupon')->where(['id'=>$id])->field('createnum,send_num')->find();
        $remaining=$remain['createnum']-$remain['send_num'];
        if($remaining==0){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'您来晚一步，已发放完'));
        }else{
            $count=M('coupon_list')->where(['cid'=>$id,'uid'=>$uid])->count();
            if($count>0){
                return json_encode(array('status'=>0,'data'=>'','msg'=>'您已领取，仅限一张'));
            }else{
                $data['cid']=$id;
                $data['uid']=$uid;
                $data['type']=4;
                $data['send_time']=time();
                $res=M('coupon_list')->add($data);
                if($res){
                    $datas['send_num']=$remain['send_num']+1;
                    M('coupon')->where(['id'=>$id])->save($datas);
                    return json_encode(array('status'=>1,'data'=>'','msg'=>'优惠券领取成功'));
                }else{
                    return json_encode(array('status'=>0,'data'=>'','msg'=>'领取失败，请重新领取'));
                }
            }
        }
    }
    //我的优惠券
    public function myCoupon()
    {
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
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
 //支付完成随机发放优惠券一张
    public function activityCoupon(){
        $uid=I('user_id');
        $money=I('money');
        if(!$uid){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'参数错误'));
        }
        $sql="select * from tp_coupon where ".$money.">`condition` and send_num<createnum and type=0 and status=1 and send_start_time<".time()." and send_end_time>".time();
        $couponList=M('coupon')->query($sql);
        $number=rand(0,count($couponList)-1);
        $couponList[$number]['send_start_time']=date('Y-m-d H:i');
        $couponList[$number]['send_end_time']=date('Y-m-d H:i');
        $couponList[$number]['use_start_time']=date('Y-m-d H:i');
        $couponList[$number]['use_end_time']=date('Y-m-d H:i');
        if($couponList){
            $data['cid']=$couponList[$number]['id'];
            $data['uid']=$uid;
            $data['type']=1;
            $data['status']=0;
            $data['send_time']=time();
            $res=M('coupon_list')->add($data);
            if($res){
                return json_encode(array('status'=>1,'data'=>$couponList[$number],'msg'=>'领取成功'));
            }else{
                return json_encode(array('status'=>0,'data'=>'','msg'=>'领取失败'));
            }
        }else{
            return json_encode(array('status'=>0,'data'=>'骚年，来迟一步，已领完','msg'=>'已领完'));
        }
    }
    
}