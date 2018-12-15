<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:47:"./application/admin/view2/order\edit_order.html";i:1539316610;s:44:"./application/admin/view2/public\layout.html";i:1528854675;}*/ ?>
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
<script type="text/javascript" src="__ROOT__/public/static/js/layer/laydate/laydate.js"></script>
<style type="text/css">
html, body {
	overflow: visible;
}

a.btn {
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

 a.red:hover {
    background-color: #e84c3d;
    border-color: #c1392b;
    color: #fff;
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
        <h3>修改订单</h3>
        <h5>修改订单收货人信息</h5>
      </div>
    </div>
  </div>
    <!-- 操作说明 -->
    <div id="explanation" class="explanation" style=" width: 99%; height: 100%;">
        <div id="checkZoom" class="title"><i class="fa fa-lightbulb-o"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span title="收起提示" id="explanationZoom" style="display: block;"></span>
        </div>
        <ul>
            <li>1.修改支付时间，同时会修改为该笔订单为已支付金额,谨慎修改</li>
            <li>2.修改快递单号，同时会修改为该笔订单为已寄给客户,谨慎修改</li>
            <li>3.修改收货时间，同时会修改为该笔订单为客户已收到,谨慎修改</li>
        </ul>
    </div>
  <form class="form-horizontal" action="<?php echo U('Admin/Order/edit_order'); ?>" id="order-add" method="post">    
    <div class="ncap-form-default">
	  <dl class="row">
        <dt class="tit">
          <label for="consignee">下单人</label>
        </dt>
        <dd class="opt">
          <input type="text" name="consignee" id="consignee" value="<?php echo $order['consignee']; ?>" class="input-txt" placeholder="下单人名字" />
        </dd>
      </dl>
        <dl class="row">
            <dt class="tit">
                <label><em></em>订单总额</label>
            </dt>
            <dd class="opt">
                <input type="text" name="goods_price" readonly="readonly" id="goods_price" value="<?php echo $order['goods_price']; ?>" class="input-txt" placeholder="下单金额" />
            </dd>
        </dl>
      <dl class="row">
        <dt class="tit">
          <label for="consignee">手机</label>
        </dt>
        <dd class="opt">
          <input type="text" name="mobile" id="mobile" value="<?php echo $order['mobile']; ?>" class="input-txt" placeholder="下单人联系电话" />
        </dd>
      </dl>
        <div>
            <div style="width: 16%;">
            <dl class="row">
            <dt class="tit" style="width: 100%;">
                <label for="consignee">步骤一</label>
            </dt>
            <dd class="opt">
                <input type="text" id="first_start_time" class="qsbox"  name="first_start_time" id="first_start_time" value="<?php echo $order['first_start_time']; ?>" class="input-txt" placeholder="交互时间" />
                <p class="notic">交互时间,格式：2018/02/15</p>
            </dd>
            <dd class="opt">
                <input type="text" name="first_end_time" id="first_end_time" value="<?php echo $order['first_end_time']; ?>" class="input-txt" placeholder="完成时间" />
                <p class="notic">完成时间,格式：2018/02/15</p>
            </dd>
            <dd class="opt">
                <input type="text" name="first_content" id="first_content" value="<?php echo $order['first_content']; ?>" class="input-txt" placeholder="描述" />
                <p class="notic">描述内容，不超过10个字</p>
            </dd>
            </dl>
        </div>

        <div style="width: 16%;position: absolute;left: 17%;top:55.8%;">
        <dl class="row">
        <dt class="tit" style="width: 100%;">
            <label for="consignee">步骤二</label>
        </dt>
        <dd class="opt">
            <input type="text" name="seacond_start_time" id="seacond_start_time" value="<?php echo $order['seacond_start_time']; ?>" class="input-txt" placeholder="交互时间" />
            <p class="notic">交互时间,格式：2018/02/15</p>
        </dd>
        <dd class="opt">
            <input type="text" name="seacond_end_time" id="seacond_end_time" value="<?php echo $order['seacond_end_time']; ?>" class="input-txt" placeholder="完成时间" />
            <p class="notic">完成时间,格式：2018/02/15</p>
        </dd>
        <dd class="opt">
            <input type="text" name="seacond_content" id="seacond_content" value="<?php echo $order['seacond_content']; ?>" class="input-txt" placeholder="描述" />
            <p class="notic">描述内容，不超过10个字</p>
        </dd>
        </dl>
    </div>
      <div style="width: 16%;position: absolute;left: 34%;top: 55.8%;">
      <dl class="row">
      <dt class="tit" style="width: 100%;">
          <label for="consignee">步骤三</label>
      </dt>
      <dd class="opt">
          <input type="text" name="third_start_time" id="third_start_time" value="<?php echo $order['third_start_time']; ?>" class="input-txt" placeholder="交互时间" />
          <p class="notic">交互时间,格式：2018/02/15</p>
      </dd>
      <dd class="opt">
          <input type="text" name="third_end_time" id="third_end_time" value="<?php echo $order['third_end_time']; ?>" class="input-txt" placeholder="完成时间" />
          <p class="notic">完成时间,格式：2018/02/15</p>
      </dd>
      <dd class="opt">
          <input type="text" name="third_content" id="third_content" value="<?php echo $order['third_content']; ?>" class="input-txt" placeholder="描述" />
          <p class="notic">描述内容，不超过10个字</p>
      </dd>
      </dl>
</div>
<div style="width: 16%;position: absolute;left: 51%;top: 55.8%;">
<dl class="row">
<dt class="tit" style="width: 100%;">
    <label for="consignee">步骤四</label>
</dt>
<dd class="opt">
    <input type="text" name="four_start_time" id="four_start_time" value="<?php echo $order['four_start_time']; ?>" class="input-txt" placeholder="交互时间" />
    <p class="notic">交互时间,格式：2018/02/15</p>
</dd>
<dd class="opt">
    <input type="text" name="four_end_time" id="four_end_time" value="<?php echo $order['four_end_time']; ?>" class="input-txt" placeholder="完成时间" />
    <p class="notic">完成时间,格式：2018/02/15</p>
</dd>
<dd class="opt">
    <input type="text" name="four_content" id="four_content" value="<?php echo $order['four_content']; ?>" class="input-txt" placeholder="描述" />
    <p class="notic">描述内容，不超过10个字</p>
</dd>
</dl>
</div>

<div style="width: 16%;position: absolute;left: 68%;top: 55.8%;">
<dl class="row">
<dt class="tit" style="width: 100%;">
    <label for="consignee">步骤五</label>
</dt>
<dd class="opt">
    <input type="text" name="five_start_time" id="five_start_time" value="<?php echo $order['five_start_time']; ?>" class="input-txt" placeholder="交互时间" />
    <p class="notic">交互时间,格式：2018/02/15</p>
</dd>
<dd class="opt">
    <input type="text" name="five_end_time" id="five_end_time" value="<?php echo $order['five_end_time']; ?>" class="input-txt" placeholder="完成时间" />
    <p class="notic">完成时间,格式：2018/02/15</p>
</dd>
<dd class="opt">
    <input type="text" name="five_content" id="five_content" value="<?php echo $order['five_content']; ?>" class="input-txt" placeholder="描述" />
    <p class="notic">描述内容，不超过10个字</p>
</dd>
</dl>
</div>
            <div style="width: 16%;position: absolute;left: 85%;top: 55.8%;">
                <dl class="row">
                    <dt class="tit" style="width: 100%;">
                        <label for="consignee">步骤六</label>
                    </dt>
                    <dd class="opt">
                        <input type="text" name="six_start_time" id="six_start_time" value="<?php echo $order['six_start_time']; ?>" class="input-txt" placeholder="交互时间" />
                        <p class="notic">交互时间,格式：2018/02/15</p>
                    </dd>
                    <dd class="opt">
                        <input type="text" name="six_end_time" id="six_end_time" value="<?php echo $order['six_end_time']; ?>" class="input-txt" placeholder="完成时间" />
                        <p class="notic">完成时间,格式：2018/02/15</p>
                    </dd>
                    <dd class="opt">
                        <input type="text" name="six_content" id="six_content" value="<?php echo $order['six_content']; ?>" class="input-txt" placeholder="描述" />
                        <p class="notic">描述内容，不超过10个字</p>
                    </dd>
                </dl>
            </div>
</div>
        <dl class="row">
            <dt class="tit">
                <label for="consignee">支付时间</label>
            </dt>
            <dd class="opt">
                <input type="text" name="pay_time" id="pay_time" value="<?php echo $order['pay_time']; ?>" class="input-txt" placeholder="支付时间" />
                <p class="notic">格式：2018-02-15 12:15</p>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label for="consignee">快递单号</label>
            </dt>
            <dd class="opt">
                <input type="text" name="shipping_code" id="shipping_code" value="<?php echo $order['shipping_code']; ?>" class="input-txt" placeholder="快递单号" />
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label for="consignee">收货时间</label>
            </dt>
            <dd class="opt">
                <input type="text" name="confirm_time" id="confirm_time" value="<?php echo $order['confirm_time']; ?>" class="input-txt" placeholder="收货时间" />
                <p class="notic">格式：2018-02-15 16:48</p>
            </dd>
        </dl>

      <dl class="row">
        <dt class="tit">
          <label for="consignee">地址</label>
        </dt>
        <dd class="opt">
            <input type="text" name="province" id="province" value="<?php echo $order['province']; ?>" class="input-txt" placeholder="省" />
            <input type="text" name="city" id="city" value="<?php echo $order['city']; ?>" class="input-txt" placeholder="市" />
            <input type="text" name="twon" id="twon" value="<?php echo $order['twon']; ?>" class="input-txt" placeholder="区/县" />
            <input type="text" name="address" id="address" value="<?php echo $order['address']; ?>" class="input-txt"   placeholder="详细地址"/>
        </dd>
      </dl>
      <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
      <div class="bot"><a href="JavaScript:void(0);" onClick="checkSubmit()" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
    </div>
        
  </form>
</div>
<script type="text/javascript">
/* 用户订单区域选择 */
$(document).ready(function(){
    $('#first_start_time').layDate();
    $('#first_end_time').layDate();
    $('#seacond_start_time').layDate();
    $('#seacond_end_time').layDate();
    $('#third_start_time').layDate();
    $('#third_end_time').layDate();
    $('#four_start_time').layDate();
    $('#four_end_time').layDate();
    $('#five_start_time').layDate();
    $('#five_end_time').layDate();
    $('#six_start_time').layDate();
    $('#six_end_time').layDate();
	$('#province').val(<?php echo $order['province']; ?>);
	$('#city').val(<?php echo $order['city']; ?>);
	$('#district').val(<?php echo $order['district']; ?>);
	$('#shipping_id').val(<?php echo $order['shipping_id']; ?>);
});
// 选择商品
function selectGoods(){
    var url = "<?php echo U('Admin/Order/search_goods'); ?>";
    layer.open({
        type: 2,
        title: '选择商品',
        shadeClose: true,
        shade: 0.8,
        area: ['60%', '60%'],
        content: url, 
    });
}

// 选择商品返回
function call_back(table_html)
{ 
	$('#goods_td').empty().html('<table id="new_table" class="table table-bordered">'+table_html+'</table>');
	//过滤选择重复商品
	$('input[name*="spec"]').each(function(i,o){
		if($(o).val()){
			var name='goods_id['+$(o).attr('rel')+']['+$(o).val()+'][goods_num]';
			$('input[name="'+name+'"]').parent().parent().parent().remove();
		}
	});
	layer.closeAll('iframe');
}

function delRow(obj){
	$(obj).parent().parent().parent().remove();
	var length = $("#goos_table tr").length;
	if(length == 0){
		$('#goods_td').empty();
	}
}

function checkSubmit()
{							
	$("span[id^='err_']").each(function(){
		$(this).hide();
	});
   ($.trim($('#consignee').val()) == '') && $('#err_consignee').show();
   ($.trim($('#province').val()) == '') && $('#err_address').show();
   ($.trim($('#city').val()) == '') && $('#err_address').show();
   ($.trim($('#district').val()) == '') && $('#err_address').show();
   ($.trim($('#address').val()) == '') && $('#err_address').show();
   ($.trim($('#mobile').val()) == '') && $('#err_mobile').show();
   if($("span[id^='err_']:visible").length > 0 ) 
      return false;							  
   $('#order-add').submit();	  
}
</script>
</body>
</html>