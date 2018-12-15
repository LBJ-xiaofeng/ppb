<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:46:"./application/admin/view2/order\add_order.html";i:1541401069;s:44:"./application/admin/view2/public\layout.html";i:1528854675;}*/ ?>
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
.adduser{
  display: none;
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
        <h3>添加订单</h3>
        <h5>管理员在后台添加一个新订单</h5>
      </div>
    </div>
  </div>
  <form class="form-horizontal" action="<?php echo U('Admin/Order/add_order'); ?>" id="order-add" method="post">    
    <div class="ncap-form-default">
    <dl class="row">
       <dt class="tit">
         <label><em></em>账号</label>
       </dt>
       <dd class="opt">
         <input type="text" name="user_name" id="user_name" class="input-txt" value="<?php echo $mobile; ?>" placeholder="手机或邮箱搜索" />
         <select name="user_id" id="user_id" >
             <option value="0">匿名用户</option>
         </select>
         <a href="javascript:void(0);" onclick="search_user();" class="ncap-btn ncap-btn-green" ><i class="fa fa-search"></i>搜索</a>
         <a id='href'><div style="width: 50px;height: 25px;background: #4fc0e8;border-radius: 3px;color: #fff;cursor: pointer;position: absolute;left: 25%;top: 51%;" class="ncap-btn ncap-btn-green adduser">添加客户</div></a>
       </dd>
      </dl>
    <dl class="row">
        <dt class="tit">
          <label for="consignee"><em>*</em>下单人</label>
        </dt>
        <dd class="opt">
          <input type="text" name="consignee" id="consignee" class="input-txt" placeholder="收货人名字" />
        </dd>
      </dl>  
      <dl class="row">
        <dt class="tit">
          <label for="consignee"><em>*</em>手机</label>
        </dt>
        <dd class="opt">
          <input type="text" name="mobile" id="mobile" class="input-txt" placeholder="收货人联系电话" />
        </dd>
      </dl>      
      <dl class="row">
        <dt class="tit">
          <label for="consignee"><em>*</em>地址</label>
        </dt>
        <dd class="opt">
          <select id="province" name="province"  title="请选择所在省份">
               <option value="">选择省份</option>
               <?php if(is_array($province) || $province instanceof \think\Collection || $province instanceof \think\Paginator): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                   <option value="<?php echo $vo['id']; ?>" ><?php echo $vo['name']; ?></option>
               <?php endforeach; endif; else: echo "" ;endif; ?>
           </select>
           <select id="city" name="city" title="请选择所在城市">
                <option value="">选择城市</option>
            </select>
            <select id="twon" name="twon" title="请选择所在区县">
                <option value="">选择县/区</option>
            </select>
            <input type="text" name="address" id="address" class="input-txt"   placeholder="详细地址"/>
        </dd>
      </dl>
     <dl class="row">
        <dt class="tit">
          <label for="invoice_title">选择订单物品</label>
        </dt>
        <dd class="opt">
           <select name="cat_id" id="first">
           <option value="">--请选择物品--</option>
           <?php if(is_array($goods) || $goods instanceof \think\Collection || $goods instanceof \think\Paginator): if( count($goods)==0 ) : echo "" ;else: foreach($goods as $key=>$goods): ?>
             <option value="<?php echo $goods['id']; ?>"><?php echo $goods['mobile_name']; ?></option>
           <?php endforeach; endif; else: echo "" ;endif; ?>
           </select>
           <select name="attr_id" id="two">
             <option value="">--请选择种类--</option>
           </select>
           <select name="brand_id" id="three">
             <option value="">--请选择品牌--</option>
           </select>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit">管理员备注</dt>
        <dd class="opt">
        <textarea class="tarea" style="width:440px; height:150px;" name="admin_note" id="admin_note">管理员添加订单</textarea>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
      <div class="bot"><a href="JavaScript:void(0);" onClick="checkSubmit()" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
    </div>
        
  </form>
</div>
<script type="text/javascript">
    //选择种类
    $(function(){
        $(document).on("change",'#first',function(){
            var id=$('#first').val();
            alert(id);
            $.ajax({
                type:'GET',
                url:"<?php echo U('Admin/Order/changeData'); ?>",
                data:{id:id},
                dataType:'JSON',
                success:function(data){
                    var html='';
                    var brand='';
                    for(i=0;i<data.data.attr.length;i++){
                        html+=`<option value="${data.data.attr[i].goods_attr_id}">${data.data.attr[i].attr_name}</option>`;
                    }
                    for(i=0;i<data.data.brand.length;i++){
                        brand+=`<option value="${data.data.brand[i].id}">${data.data.brand[i].name}</option>`;
                    }
                    $('#two').html(html);
                    $('#three').html(brand);
                }
            });
        })
    })
        //选择地址市
    $(function(){
        $(document).on("blur",'#province',function(){
            var id=$('#province').val();
            $.ajax({
                type:'GET',
                url:"<?php echo U('Admin/Order/provinceToCity'); ?>",
                data:{id:id},
                dataType:'JSON',
                success:function(data){
                    var html='';
                    for(i=0;i<data.data.length;i++){
                        html+=`<option value="${data.data[i].id}">${data.data[i].name}</option>`;
                    }
                    $('#city').html(html);
                }
            });
        })
    })
           //选择地址县区
    $(function(){
        $(document).on("blur",'#city',function(){
            var id=$('#city').val();
            $.ajax({
                type:'GET',
                url:"<?php echo U('Admin/Order/cityToTwon'); ?>",
                data:{id:id},
                dataType:'JSON',
                success:function(data){
                    console.log(data)
                    var html='';
                    for(i=0;i<data.data.length;i++){
                        html+=`<option value="${data.data[i].id}">${data.data[i].name}</option>`;
                    }
                    $('#twon').html(html);
                }
            });
        })
    })
  //搜索用户 
  function search_user(){
    var user_name = $('#user_name').val();
    if($.trim(user_name) == '')
      return false;
      $.ajax({
              type : "POST",
              url:"/index.php?m=Admin&c=User&a=search_user",//+tab,
              data :{search_key:$('#user_name').val()},// 你的formid
              success: function(data){
                if(data){
                  data = data + '<option value="0">匿名用户</option>';
                  $('#user_id').html(data);
                }else{
                  $('#href').attr('href',"<?php echo U('admin/user/add_user'); ?>?mobile="+$('#user_name').val())
                  $('.adduser').removeClass('adduser');
                      }
        }
          });   
  }
function checkSubmit(){     
  
  $('#order-add').submit(); 
 
}
</script>
</body>
</html>