<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:43:"./application/admin/view2/order\detail.html";i:1541483333;s:44:"./application/admin/view2/public\layout.html";i:1528854675;}*/ ?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link href="__PUBLIC__/static/css/main.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/static/css/page.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/static/font/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="__PUBLIC__/static/font/css/font-awesome-ie7.min.css">
<![endif]-->
<link href="__PUBLIC__/static/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="__PUBLIC__/static/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">html, body { overflow: visible;}</style>
<script type="text/javascript" src="__PUBLIC__/static/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/layer/layer.js"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
<script type="text/javascript" src="__PUBLIC__/static/js/admin.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/jquery.mousewheel.js"></script>
<script src="__PUBLIC__/js/myFormValidate.js"></script>
<script src="__PUBLIC__/js/myAjax2.js"></script>
<script src="__PUBLIC__/js/global.js"></script>
    <script type="text/javascript">
    function delfunc(obj){
    	layer.confirm('确认删除？', {
    		  btn: ['确定','取消'] //按钮
    		}, function(){
    		    // 确定
   				$.ajax({
   					type : 'post',
   					url : $(obj).attr('data-url'),
   					data : {act:'del',del_id:$(obj).attr('data-id')},
   					dataType : 'json',
   					success : function(data){
						layer.closeAll();
   						if(data==1){
   							layer.msg('操作成功', {icon: 1});
   							$(obj).parent().parent().parent().remove();
   						}else{
   							layer.msg(data, {icon: 2,time: 2000});
   						}
   					}
   				})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }

    function selectAll(name,obj){
    	$('input[name*='+name+']').prop('checked', $(obj).checked);
    }

    function delAll(obj,name){
    	var a = [];
    	$('input[name*='+name+']').each(function(i,o){
    		if($(o).is(':checked')){
    			a.push($(o).val());
    		}
    	})
    	if(a.length == 0){
    		layer.alert('请选择删除项', {icon: 2});
    		return;
    	}
    	layer.confirm('确认删除？', {btn: ['确定','取消'] }, function(){
    			$.ajax({
    				type : 'get',
    				url : $(obj).attr('data-url'),
    				data : {act:'del',del_id:a},
    				dataType : 'json',
    				success : function(data){
						layer.closeAll();
    					if(data == 1){
    						layer.msg('操作成功', {icon: 1});
    						$('input[name*='+name+']').each(function(i,o){
    							if($(o).is(':checked')){
    								$(o).parent().parent().remove();
    							}
    						})
    					}else{
    						layer.msg(data, {icon: 2,time: 2000});
    					}
    				}
    			})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }

    /**
     * 全选
     * @param obj
     */
    function checkAllSign(obj){
        $(obj).toggleClass('trSelected');
        if($(obj).hasClass('trSelected')){
            $('#flexigrid > table>tbody >tr').addClass('trSelected');
        }else{
            $('#flexigrid > table>tbody >tr').removeClass('trSelected');
        }
    }
    /**
     * 批量公共操作（删，改）
     * @returns {boolean}
     */
    function publicHandleAll(type){
        var ids = '';
        $('#flexigrid .trSelected').each(function(i,o){
//            ids.push($(o).data('id'));
            ids += $(o).data('id')+',';
        });
        if(ids == ''){
            layer.msg('至少选择一项', {icon: 2, time: 2000});
            return false;
        }
        publicHandle(ids,type); //调用删除函数
    }
    /**
     * 公共操作（删，改）
     * @param type
     * @returns {boolean}
     */
    function publicHandle(ids,id,handle_type){
        layer.confirm('确认当前操作？', {
                    btn: ['确定', '取消'] //按钮
                }, function () {
                    // 确定
                    $.ajax({
                        url: $('#flexigrid').data('url'),
                        type:'post',
                        data:{ids:ids,id:id,type:handle_type},
                        dataType:'JSON',
                        success: function (data) {
                            layer.closeAll();
                            if (data.status == 1){
                                console.log(data);
                                layer.msg(data.msg, {icon: 1, time: 2000},function(){
                                    location.href = data.url;
                                });
                            }else{
                                layer.msg(data.msg, {icon: 2, time: 2000});
                            }
                        }
                    });
                }, function (index) {
                    layer.close(index);
                }
        );
    }
</script>  

</head>
<style>
    .ncm-goods-gift {
        text-align: left;
    }
    .ncm-goods-gift ul {
        display: inline-block;
        font-size: 0;
        vertical-align: middle;
    }
    .ncm-goods-gift li {
        display: inline-block;
        letter-spacing: normal;
        margin-right: 4px;
        vertical-align: top;
        word-spacing: normal;
    }
    .ncm-goods-gift li a {
        background-color: #fff;
        display: table-cell;
        height: 30px;
        line-height: 0;
        overflow: hidden;
        text-align: center;
        vertical-align: middle;
        width: 30px;
    }
    .ncm-goods-gift li a img {
        max-height: 30px;
        max-width: 30px;
    }

    a.green{

        background: #fff none repeat scroll 0 0;
        border: 1px solid #f5f5f5;
        border-radius: 4px;
        color: #999;
        cursor: pointer !important;
        display: inline-block;
        font-size: 12px;
        font-weight: normal;
        height: 20px;
        letter-spacing: normal;
        line-height: 20px;
        margin: 0 5px 0 0;
        padding: 1px 6px;
        vertical-align: top;
    }
    .qrcode{
        width: 100px;
        height: 100px;
        display: block;
    }
    .guanbi{
        boder: none;
    }

    a.green:hover { color: #FFF; background-color: #1BBC9D; border-color: #16A086; }

    .ncap-order-style .ncap-order-details{
        margin:20px auto;
    }
    .contact-info h3,.contact-info .form_class{
        display: inline-block;
        vertical-align: middle;
    }
    .form_class i.fa{
        vertical-align: text-bottom;
    }
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.go(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>订单管理</h3>
                <h5>订单查询及管理</h5>
            </div>
            <div class="subject" style="width:62%">

                <div class="no-print" style="width:92%">
                    <a href="<?php echo U('Admin/order/edit_order',array('order_id'=>$order['order_id'])); ?>" style="float:right;margin-right:10px" class="ncap-btn-big ncap-btn-green" ><i class="fa fa-pencil-square-o"></i>修改资料</a>
                    <a href="javascript:printDiv();" style="float:right;margin-right:10px" class="ncap-btn-big ncap-btn-green" ><i class="fa fa-print"></i>打印订单</a>
                    </a>
                </div>
            </div>
        </div>

    </div>
    <div class="ncap-order-style">
        <div class="titile">
            <h3></h3>
        </div>

        <div class="ncap-order-details">
            <form id="order-action">
                <div class="tabs-panels">
                    <div class="misc-info">
                        <h3>基本信息</h3>
                        <dl>
                            <dt>订单 ID：</dt>
                            <dd><?php echo $order['order_id']; ?></dd>
                            <dt>订单号：</dt>
                            <dd><?php echo $order['order_sn']; ?></dd>
                            <dt>会员：</dt>
                            <dd><?php echo $user['nickname']; ?>  ID:<?php echo $user['user_id']; ?></dd>
                        </dl>
                        <dl>
                            <dt>联系方式：</dt>
                            <dd><?php echo $order['mobile']; ?></dd>
                            <dt>订单状态：</dt>
                            <dd><?php echo $order_status[$order[order_status]]; ?></dd>
                            <dt>下单时间：</dt>
                            <dd><?php echo date('Y-m-d H:i',$order['add_time']); ?></dd>
                        </dl>
                        <dl>
                            <dt>寄回快递单号：</dt>
                            <dd><?php echo $order['shipping_code']; ?></dd>
                            <dt>支付时间：</dt>
                            <dd>
                                <?php if($order['pay_time'] > 0): ?>
                                    <?php echo date('Y-m-d H:i',$order['pay_time']); else: ?>
                                    暂未支付
                                <?php endif; ?>

                            </dd>
                            <dt>收货时间：</dt>
                            <dd>
                                <?php if($order['confirm_time'] > 0): ?>
                                    <?php echo date('Y-m-d H:i',$order['confirm_time']); else: ?>
                                    暂未收货
                                <?php endif; ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt>选择样例：</dt>
                            <dd><a href="<?php echo U('admin/Goods/addEditGoods',array('id'=>$order['goods_id'])); ?>"><?php echo getgoods($order['goods_id']); ?></a></dd>
                        </dl>
                    </div>
                    <div class="addr-note">
                        <h4>下单信息</h4>
                        <dl>
                            <dt>下单人：</dt>
                            <dd><?php echo $order['consignee']; ?></dd>
                            <dt>联系方式：</dt>
                            <dd><?php echo $order['mobile']; ?></dd>
                            <dt>收货地址：</dt>
                            <dd><?php echo $order['province']; ?><?php echo $order['city']; ?><?php echo $order['twon']; ?><?php echo $order['address']; ?></dd>
                        </dl>
                        <dl>
                            <dt>订单处理方式：</dt>
                            <dd><?php if($order['type'] == 0): ?>预约取件<?php else: ?>自己邮寄<?php endif; ?></dd>
                            <?php if($order['type'] != 1): ?>
                                <dt>快递单号：</dt>
                                <dd><?php echo $order['send_shipping_code']; ?><a>(<?php echo $order['send_shipping_name']; ?>)</a></dd>
                                <dt>寄件时间：</dt>
                                <dd><?php echo date("Y-m-d H:i",$order['send_shipping_time']); ?></dd>
                            <?php endif; ?>
                        </dl>
                    </div>
                    <div class="addre-note">
                        <h4>订单二维码</h4>
                        <div id="clickQrcode">
                            <span style="width: 200px;height: 40px;display:block;color:#fff;background-color: #4fc0e8;border-color: #3aa8cf;border-radius: 5px;font-size: 20px;font-weight: bold;line-height: 40px;text-align: center;cursor: pointer;" datoid="<?php echo $order['order_id']; ?>" status="<?php echo $order['order_status']; ?>" onclick="getQrcode(this)">点击查看二维码</span>
                        </div>
                        <div id="qrcode"></div>
                    </div>


                    <div class="goods-info">
                        <h4>订单信息</h4>
                        <table>
                            <thead>
                            <tr>
                                <th>订单号</th>
                                <th>订单信息</th>
                                <th>数量</th>
                                <th>实际金额</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="w60"><?php echo $order['order_sn']; ?></td>
                                <td class="w60"><?php echo $order['mobile_name']; ?>/<?php echo $order['attr_name']; ?>/<?php echo $order['brand_name']; ?><br/></td>
                                <td class="w60"><?php echo $order['goods_num']; ?></td>
                                <td class="w80"><?php echo $order['goods_price']; ?></td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    <div class="goods-info">
                    <h4>价格信息<span style="font-size: 10px;color:red;">(实际金额根据价格信息条目变化)</span></h4>
                        <a style="width: 70px;height: 30px;color:#fff;background-color: #4fc0e8;border-color: #3aa8cf;border-radius: 5px;font-size: 14px;line-height: 30px;font-weight: bold;text-align: center;display: block;" href="<?php echo U('admin/Order/addprice',array('oid'=>$order['order_id'],'act'=>'add')); ?>">添加价格</a>
                        <table>
                            <thead>
                            <tr>
                                <th>价格简述</th>
                                <th>金额</th>
                                <th>操作人</th>
                                <th>添加时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($money) || $money instanceof \think\Collection || $money instanceof \think\Paginator): if( count($money)==0 ) : echo "" ;else: foreach($money as $key=>$val): ?>
                            <tr>
                                <td class="w60"><?php echo $val['title']; ?></td>
                                <td class="w60"><?php echo $val['money']; ?></td>
                                <td class="w80"><?php echo getAdmin($val['adminid']); ?></td>
                                <td class="w80"><?php echo date("Y-m-d H:i:s",$val['add_time']); ?></td>
                                <td class="w80">
                                    <a style="width: 50px;height: 30px;color:#fff;background-color: #4fc0e8;border-color: #3aa8cf;border-radius: 5px;font-size: 14px;line-height: 30px;font-weight: bold;text-align: center;display: inline-block;" href="<?php echo U('admin/Order/addprice',array('oid'=>$val['oid'],'id'=>$val['id'],'act'=>'edit')); ?>">编辑</a>
                                    <a style="width: 50px;height: 30px;color:#fff;background-color: #4fc0e8;border-color: #3aa8cf;border-radius: 5px;font-size: 14px;line-height: 30px;font-weight: bold;text-align: center;display: inline-block;" dataoid="<?php echo $val['oid']; ?>" dataid="<?php echo $val['id']; ?>" onclick="delprice(this)" href="javascript:void(0);">删除</a>
                                </td>
                            </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>

                        </tbody>
                        </table>
                    </div>
                    <div class="goods-info">
                    <h4>定损信息</h4>
                    <div style="width: 100%;">
                        <div style="display: inline-flex;width: 100%;">
                         <?php if($order['order_prom_type']): if(is_array($order['order_prom_type']) || $order['order_prom_type'] instanceof \think\Collection || $order['order_prom_type'] instanceof \think\Paginator): if( count($order['order_prom_type'])==0 ) : echo "" ;else: foreach($order['order_prom_type'] as $key=>$val): ?>
                            <p style="width:18%;height:130px;border:1px solid #eee;margin-left: 2%;"><img style="height:130px;width: 100%;" src="<?php echo $val; ?>"/></p>
                        <?php endforeach; endif; else: echo "" ;endif; else: ?>
                        <span>定损暂无相关信息</span>
                        <?php endif; ?>
                        </div>
                    </div>
                    </div>
                    <div class="goods-info">
                    <h4>客户选择维护图片</h4>
                    <div style="width: 100%;">
                        <div style="display: inline-flex;width: 100%;">
                        <?php if($order['checkedimg']): if(is_array($order['checkedimg']) || $order['checkedimg'] instanceof \think\Collection || $order['checkedimg'] instanceof \think\Paginator): if( count($order['checkedimg'])==0 ) : echo "" ;else: foreach($order['checkedimg'] as $key=>$val): ?>
                            <p style="width:18%;height:130px;border:1px solid #eee;margin-left: 2%;"><img style="height:130px;width: 100%;" src="<?php echo $val; ?>"/></p>
                        <?php endforeach; endif; else: echo "" ;endif; else: ?>
                        <span>暂无相关信息</span>
                    <?php endif; ?>
                        </div>
                    </div>
                    <h4>质检图片</h4>
                    <div style="width: 100%;">
                        <div style="display: inline-flex;width: 100%;">
                        <?php if($order['integral']): if(is_array($order['integral']) || $order['integral'] instanceof \think\Collection || $order['integral'] instanceof \think\Paginator): if( count($order['integral'])==0 ) : echo "" ;else: foreach($order['integral'] as $key=>$val): ?>
                            <p style="width:18%;height:130px;border:1px solid #eee;margin-left: 2%;"><img style="height:130px;width: 100%;" src="<?php echo $val; ?>"/></p>
                        <?php endforeach; endif; else: echo "" ;endif; else: ?>
                        <span>质检暂无相关信息</span>
                    <?php endif; ?>
                        </div>
                    </div>
                    </div>
                    <div class="goods-info">
                        <h4>进度情况概览</h4>
                        <table>
                            <thead>
                            <tr>
                                <th>步骤</th>
                                <th>操作人</th>
                                <th>交互时间</th>
                                <th>完成时间</th>
                                <th>工艺描述</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="w60"><?php if($contact[0][contact]): ?><?php echo $contact[0]['contact']; else: ?>步骤一<?php endif; ?><br/></td>
                                <td class="w60">
                                        <?php echo getAdmin($order['first_admin']); ?>
                                </td>
                                <td class="w60">
                                    <?php if($order['first_start_time'] > 0): ?>
                                        <?php echo $order['first_start_time']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                                <td class="w60">
                                    <?php if($order['first_end_time'] > 0): ?>
                                        <?php echo $order['first_end_time']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                                <td class="w80">
                                    <?php if($order['first_content']): ?>
                                        <?php echo $order['first_content']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="w60"><?php if($contact[1][contact]): ?><?php echo $contact[1]['contact']; else: ?>步骤二<?php endif; ?><br/></td>
                                <td class="w60">
                                    <?php echo getAdmin($order['seacond_admin']); ?>
                                </td>
                                <td class="w60">
                                    <?php if($order['seacond_start_time'] > 0): ?>
                                        <?php echo $order['seacond_start_time']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                                <td class="w60">
                                    <?php if($order['seacond_end_time'] > 0): ?>
                                        <?php echo $order['seacond_end_time']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                                <td class="w80">
                                    <?php if($order['seacond_content']): ?>
                                        <?php echo $order['seacond_content']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="w60"><?php if($contact[2][contact]): ?><?php echo $contact[2]['contact']; else: ?>步骤三<?php endif; ?><br/></td>
                                <td class="w60">
                                    <?php echo getAdmin($order['third_admin']); ?>
                                </td>
                                <td class="w60">
                                    <?php if($order['third_start_time'] > 0): ?>
                                        <?php echo $order['third_start_time']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                                <td class="w60">
                                    <?php if($order['third_end_time'] > 0): ?>
                                        <?php echo $order['third_end_time']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                                <td class="w80">
                                    <?php if($order['third_content']): ?>
                                        <?php echo $order['third_content']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="w60"><?php if($contact[3][contact]): ?><?php echo $contact[3]['contact']; else: ?>步骤四<?php endif; ?><br/></td>
                                <td class="w60">
                                    <?php echo getAdmin($order['four_admin']); ?>
                                </td>
                                <td class="w60">
                                    <?php if($order['four_start_time'] > 0): ?>
                                        <?php echo $order['four_start_time']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                                <td class="w60">
                                    <?php if($order['four_end_time'] > 0): ?>
                                        <?php echo $order['four_end_time']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                                <td class="w80">
                                    <?php if($order['four_content']): ?>
                                        <?php echo $order['four_content']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="w60"><?php if($contact[4][contact]): ?><?php echo $contact[4]['contact']; else: ?>步骤五<?php endif; ?><br/></td>
                                <td class="w60">
                                    <?php echo getAdmin($order['five_admin']); ?>
                                </td>
                                <td class="w60">
                                    <?php if($order['five_start_time'] > 0): ?>
                                        <?php echo $order['five_start_time']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                                <td class="w60">
                                    <?php if($order['five_end_time'] > 0): ?>
                                        <?php echo $order['five_end_time']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                                <td class="w80">
                                    <?php if($order['five_content']): ?>
                                        <?php echo $order['five_content']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="w60"><?php if($contact[5][contact]): ?><?php echo $contact[5]['contact']; else: ?>步骤六<?php endif; ?><br/></td>
                                <td class="w60">
                                    <?php echo getAdmin($order['six_admin']); ?>
                                </td>
                                <td class="w60">
                                    <?php if($order['six_start_time'] > 0): ?>
                                        <?php echo $order['six_start_time']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                                <td class="w60">
                                    <?php if($order['six_end_time'] > 0): ?>
                                        <?php echo $order['six_end_time']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                                <td class="w80">
                                    <?php if($order['six_content']): ?>
                                        <?php echo $order['six_content']; else: ?>
                                        暂无
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    <div class="total-amount contact-info">
                        <h3>订单总额：￥<?php echo $order['goods_price']; ?></h3>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function delfun() {
        // 删除按钮
        layer.confirm('确认删除？', {
            btn: ['确定'] //按钮
        }, function () {
            console.log("确定");
        }, function () {
            console.log("取消");
        });
    }
    function printDiv()
    {
        var oldStr = document.body.innerHTML;
        $('.no-print').hide();
        $('.fixed-bar').hide();
        window.print();
        document.body.innerHTML = oldStr;
    }
    function getQrcode(e){
        var oid=$(e).attr('datoid');
        var status=$(e).attr('status');
       if(status>1){

        $.ajax({
            url:"<?php echo U('admin/order/makecode'); ?>",
            type:'get',
            data:{'oid':oid},
            dataType:'json',
            success:function(res){
                var html='';
                html+=`
                <img style="width:150px;height:150px;position: absolute;top: 18.4%;left: 30%;" src="${res.data}">
        `;
                $('#qrcode').addClass('qrcode');
                $('#qrcode').html(html);
            },
            error:function(){

            }
        })

       }else{
          alert('该订单暂无二维码')
       }
    }
    function delprice(e){
        var oid=$(e).attr('dataoid');
        var id=$(e).attr('dataid');
            $.ajax({
                type: "POST",
                url: "<?php echo U('Admin/order/addprice'); ?>",
                data: {oid:oid,id:id},
                dataType: "json",
                error: function () {
                    layer.alert("服务器繁忙, 请联系管理员!");
                },
                success: function (data) {
                    if (data.status==1) {
                        layer.msg(data.msg, {icon: 1});
                        location.href = "<?php echo U('Admin/order/detail'); ?>?order_id="+oid;
                    } else {
                        layer.msg(data.msg, {icon: 2});
                    }
                }
            })
    }

</script>
</body>
</html>