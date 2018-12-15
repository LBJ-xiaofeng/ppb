<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:42:"./application/admin/view2/user\detail.html";i:1536288621;s:44:"./application/admin/view2/public\layout.html";i:1528854675;}*/ ?>
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
    td{height:40px;line-height:40px; padding-left:20px;}
    .span_1{
        float:left;
        margin-left:0px;
        width:25%;
        line-height:130px;
        text-align: center;
        font-weight: bold;
        font-size: 14px;
    }
    .span_1 ul{list-style:none;padding:0px;}
    .span_1 ul li{
        border:1px solid #CCC;
        height:40px;
        padding:0px 10px;
        margin-left:-1px;
        margin-top:-1px;
        line-height:40px;
    }
    .span_2{
        float:left;
        margin-left:0px;
        width:25%;
        text-align: center;
        font-weight: bold;
        line-height:130px;
        font-size: 14px;
    }
    .span_2 ul{list-style:none;padding:0px;}
    .span_2 ul li{
        border:1px solid #CCC;
        height:40px;
        padding:0px 10px;
        margin-left:-1px;
        margin-top:-1px;
        line-height:40px;
    }
    h1{
        font-size: 18px;
    }
</style>
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>会员管理 - 会员信息</h3>
                <h5>网站系统会员管理会员信息</h5>
            </div>
        </div>
    </div>
        <div class="ncap-form-default">
            <h1>个人资料</h1>
            <dl class="row">
                <dt class="tit">
                </dt>
                <dd class="opt">
                    <div>
                        <span class="span_2">
                            <ul>
                                <li>用户ID</li>
                                <!--<li>电子邮箱</li>-->
                                <li>手机号码</li>
                                <li>近期登陆时间</li>
                                <li>最后登录IP</li>
                                <li>邀请人</li>
                            </ul>
                        </span>
                        <span class="span_2">
                            <ul>
                                <li><?php echo $user['user_id']; ?></li>
                                <!--<li><?php echo $user['email']; ?></li>-->
                                <li><?php echo $user['mobile']; ?></li>
                                <li><?php echo date('Y-m-d H:i',$user['last_login']); ?></li>
                                <li>
                                    <?php if(empty($user['last_ip'])): ?>
                                        暂无
                                        <?php else: ?>
                                    <?php echo $user['last_ip']; endif; ?>
                                </li>
                                <li><?php echo getNickname($user['first_leader']); ?></li>
                            </ul>
                        </span>
                        <span class="span_2">
                            <ul>
                                <li>用户昵称</li>
                                <!--<li>QQ</li>-->
                                <li>累计消费(元)</li>
                                <li>钱包余额</li>
                                <li>性别</li>
                            </ul>
                        </span>
                        <span class="span_2">
                            <ul>
                                <li><?php echo $user['nickname']; ?></li>
                                <!--<li><?php echo $user['qq']; ?></li>-->
                                <li><?php echo $user['pay_points']; ?></li>
                                <li><?php echo $user['user_money']; ?></li>
                                <li>
                                    <?php if($user['sex'] == 0): ?>
                                        保密
                                        <?php elseif($user['sex'] == 1): ?>
                                            男
                                        <?php else: ?>
                                        女
                                    <?php endif; ?>

                                </li>
                            </ul>
                        </span>
                        <div style="clear:both;"></div>
                    </div>
                </dd>
            </dl>
            <h1>个人订单</h1>
            <div class="bDiv" style="height: auto;">
                <div id="flexigrid" >
                    <table cellpadding="0" cellspacing="0" border="1" style="width: 100%">
                        <tr  style="height: 40px;" align="center">
                            <th align="center" abbr="article_title" style="width: 3.8%">
                                <div style=" " class="">订单ID</div>
                            </th>
                            <th align="center" abbr="ac_id" style="width: 12%">
                                <div style="" class="">订单号</div>
                            </th>
                            <th align="center" abbr="ac_id" style="width: 15%">
                                <div style="" class="">订单信息</div>
                            </th>
                            <th align="center" abbr="article_show" style="width: 5%">
                                <div style="text-align: center;width: 100px;" class="">订单状态</div>
                            </th>
                            <th align="center" abbr="article_time" style="width: 5%">
                                <div style="text-align: center;" class="">支付状态</div>
                            </th>
                            <th align="center" abbr="article_time" style="width: 5%">
                                <div style="text-align: center;" class="">金额</div>
                            </th>
                            <th align="center" abbr="article_time" style="width: 5%">
                                <div style="text-align: center; " class="">订单人</div>
                            </th>
                            <th align="center" abbr="article_time" style="width: 10%">
                                <div style="text-align: center; " class="">联系方式</div>
                            </th>
                            <th align="center" abbr="article_time" style="width: 10%">
                                <div style="text-align: center;width: 150px;" class="">下单时间</div>
                            </th>
                            <th align="center" axis="col1" class="handle">
                                <div style="text-align: center;">操作</div>
                            </th>
                        </tr>
                        <?php if(is_array($order) || $order instanceof \think\Collection || $order instanceof \think\Paginator): if( count($order)==0 ) : echo "" ;else: foreach($order as $key=>$val): ?>
                        <tr  style="height: 40px;" align="center">
                            <td align="center" abbr="article_title" style="width: 3.8%">
                                <div style=" " class=""><?php echo $val['order_id']; ?></div>
                            </td>
                            <td align="center" abbr="ac_id" style="width: 12%">
                                <div style="" class=""><?php echo $val['order_sn']; ?></div>
                            </td>
                            <td align="center" abbr="ac_id" style="width: 15%">
                                <div style="" class=""><?php echo $val['mobile_name']; ?>/<?php echo $val['attr_name']; ?>/<?php echo $val['brand_name']; ?>/<?php echo $val['cat_name']; ?></div>
                            </td>
                            <td align="center" abbr="article_show" style="width: 5%">
                                <div style="text-align: center;width: 100px;" class=""><?php echo getOrderStatus($val['order_status']); ?></div>
                            </td>
                            <td align="center" abbr="article_time" style="width: 5%">
                                <div style="text-align: center;" class=""><?php echo getPayStatus($val['pay_status']); ?></div>
                            </td>
                            <td align="center" abbr="article_time" style="width: 5%">
                                <div style="text-align: center;" class=""><?php echo $val['goods_price']; ?></div>
                            </td>
                            <td align="center" abbr="article_time" style="width: 5%">
                                <div style="text-align: center; " class=""><?php echo $val['consignee']; ?></div>
                            </td>
                            <td align="center" abbr="article_time" style="width: 10%">
                                <div style="text-align: center; " class=""><?php echo $val['mobile']; ?></div>
                            </td>
                            <td align="center" abbr="article_time" style="width: 10%">
                                <div style="text-align: center;width: 150px;" class=""><?php echo date('Y-m-d H:i:s',$val['add_time']); ?></div>
                            </td>
                            <td align="center" axis="col1" class="handle">
                                <a class="btn green"  href="<?php echo U('admin/Order/detail',array('order_id'=>$val[order_id])); ?>" data-url=""><i class="fa fa-pencil-square-o"></i>查看详情</a>
                            </td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </table>
                </div>
                <div class="iDiv" style="display: none;"></div>
            </div>
          </div>
</div>
</body>
</html>