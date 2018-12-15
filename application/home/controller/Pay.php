<?php
namespace app\home\controller;
use think\Controller;
use think\Request;
use think\Db;
class Pay extends Controller
{
    public function pay_order($sn,$uid,$pay_type,$goods_price,$types)
    {

        $res = M('order');
        $order = M('order')->where(['order_sn'=>$sn,'user_id'=>$uid])->select();//查询订单信息
        foreach($order as $k=>$v){
            $goods_info=M('goods')->where(['goods_id'=>$v['goods_id']])->find(); //查询商品信息
        }
        $ptype = $pay_type;//微信支付 或者余额支付
        //判断支付方式
        switch ($ptype) {
            case '0';//如果支付方式为余额
                $type['pay_code'] = 'cod';
                $type['pay_name'] = '余额支付';//更新支付方式
                $res->where(['order_sn'=>$sn,'user_id'=>$uid])->save($type);
                $body = $goods_info['goods_name'];//支付说明
                $out_trade_no = $sn;//订单号
                $total_fee = $goods_price;//支付金额
                $order=self::payCod($body,$out_trade_no,$total_fee,$types);
                echo $order;
                break;
            case '1';
                $type['pay_code'] = 'weixin';
                $type['pay_name'] = '微信支付';//更新支付方式为微信
                $res->where(['order_sn'=>$sn,'user_id'=>$uid])->save($type);
                $wxPay = new \app\home\controller\Dpay($sn,$goods_price,$goods_info['goods_name']);
                $wxPay->index($sn,$goods_price,$goods_info['goods_name'],$types);
        }
    }

//微信付款订单回调方法
    public function WxPayNotify()
    {
//微信返回的数据
        $input = file_get_contents("php://input");
        $myfile = fopen("wxtestfile.txt", "a");
        fwrite($myfile, "\r\n");
        fwrite($myfile, $input);
        if($input){
            $xml = simplexml_load_string($input);
            $money = (string)$xml->total_fee;
            $return_code = (string)$xml->return_code;
            $attach = (string)$xml->attach;
            $user_id = (string)$xml->user_id;
            $out_trade_no = (string)$xml->out_trade_no;
        }
        if($return_code){
            $order['order_status']=2;
            $order['pay_status']=1;
            $order['shipping_status']=0;
            $order['pay_time']=time();
            M('order')->where(['order_sn'=>$out_trade_no])->save($order);
            $num=M('order')->where(['order_sn'=>$out_trade_no])->field('goods_id,mobile')->select();
            foreach($num as $k=>$v){
                $gid[]=$v['goods_id'];
            }
            $gids=implode(',',$gid);
            $where['goods_id']=array('in',$gids);
            M('goods')->where($where)->setDec('sales_sum');
            $downSendSms=new Api();
            $downSendSms->send_validate_code(4,$num['mobile']);
            echo '付款成功';
        }else{
             echo '付款失败';
        }

    }
    //微信充值回调方法
    public function rechargeNotify()
    {
//微信返回的数据
        $input = file_get_contents("php://input");
        $myfile = fopen("wxtestfile.txt", "a");
        fwrite($myfile, "\r\n");
        fwrite($myfile, $input);
        if($input){
            $xml = simplexml_load_string($input);
            $money = (string)$xml->total_fee;
            $return_code = (string)$xml->return_code;
            $attach = (string)$xml->attach;
            $user_id = (string)$xml->user_id;
            $out_trade_no = (string)$xml->out_trade_no;
        }
        if($return_code){
            $user=M('recharge')->where(['order_sn'=>$out_trade_no])->getField('user_id');
            M('recharge')->where(['order_sn'=>$out_trade_no])->save(array('pay_time'=>time(),'pay_status'=>1));
            $users=M('users')->where(['user_id'=>$user])->getField('user_money');
            $countm=round($money/100,2)+$users;
            M('users')->where(['user_id'=>$user])->save(array('user_money'=>$countm));
            echo '充值成功';
        }else{
            echo 'fail';
        }

    }
    //开团,参团支付回调地址
    public function StartNotify(){
        //微信返回的数据
        $input = file_get_contents("php://input");
        $myfile = fopen("wxtestfile.txt", "a");
        fwrite($myfile, "\r\n");
        fwrite($myfile, $input);
        if($input){
            $xml = simplexml_load_string($input);
            $money = (string)$xml->total_fee;
            $return_code = (string)$xml->return_code;
            $attach = (string)$xml->attach;
            $user_id = (string)$xml->user_id;
            $out_trade_no = (string)$xml->out_trade_no;
        }
        if($return_code){
            $order['order_status']=2;
            $order['pay_status']=1;
            $order['shipping_status']=0;
            $order['pay_time']=time();
            M('order')->where(['order_sn'=>$out_trade_no])->save($order);
            $list_id=M('order')->where(['order_sn'=>$out_trade_no])->field('user_id,order_prom_type,order_prom_id')->find();
            if(isset($list_id['order_prom_id']) && $list_id['order_prom_id']>0 && $list_id['order_prom_type']==2){
                $list=M('prom_list')->where(['id'=>$list_id['order_prom_id']])->field('user_id,prom_id')->find();
                $data=explode(',',$list['user_id']);
                if(in_array($list_id['user_id'],$data)){
                    M('prom_list')->where(['id'=>$list_id['order_prom_id']])->save('status',1);
                }else{
                    $user_ids=$list.','.$list_id['user_id'];
                    M('prom_list')->where(['id'=>$list_id['order_prom_id']])->save(array('user_id'=>$user_ids));
                }
                M('prom_goods')->where(['id'=>$list['prom_id']])->setInc('buy_num',1);
            }

            echo '付款成功';
        }else{
            echo '付款失败';
        }

    }
//余额支付方法
    public function payCod($body, $out_trade_no, $total_fee,$types)
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
                    $order['order_status']=2;
                    $order['pay_status']=1;
                    $order['shipping_status']=0;
                    $order['pay_time']=time();
                    M('order')->where(['order_sn'=>$out_trade_no])->save($order);
                    $num=M('order')->where(['order_sn'=>$out_trade_no])->field('goods_id')->select();
                    foreach($num as $k=>$v){
                        $gid[]=$v['goods_id'];
                    }
                    $gids=implode(',',$gid);
                    $where['goods_id']=array('in',$gids);
                    M('goods')->where($where)->setDec('sales_sum');
                if($types==2){
                    $list_id=M('order')->where(['order_sn'=>$out_trade_no])->field('user_id,order_prom_type,order_prom_id')->find();
                    if(isset($list_id['order_prom_id']) && $list_id['order_prom_id']>0 && $list_id['order_prom_type']==2){
                        $list=M('prom_list')->where(['id'=>$list_id['order_prom_id']])->getField('user_id');
                        $data=explode(',',$list);
                        if(in_array($list_id['user_id'],$data)){
                            M('prom_list')->where(['id'=>$list_id['order_prom_id']])->setField('status',1);
                        }else{
                            $user_ids=$list.','.$list_id['user_id'];
                            M('prom_list')->where(['id'=>$list_id['order_prom_id']])->save(array('user_id'=>$user_ids));
                        }
                        M('prom_goods')->where(['id'=>$list['prom_id']])->setInc('buy_num',1);
                    }
                }

                    return json_encode(array('status'=>1,'msg'=>'支付成功','data'=>''));
            }
        }
    }

}