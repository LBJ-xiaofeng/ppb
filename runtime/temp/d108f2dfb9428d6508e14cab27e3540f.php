<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:42:"./application/admin/view2/order\index.html";i:1541390380;s:44:"./application/admin/view2/public\layout.html";i:1528854675;}*/ ?>
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

<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>订单列表</h3>
        <h5>订单查询及管理</h5>
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
      <li>查看操作可以查看订单详情</li>
      <li>已下单状态为客户在应用上填写完成并已提交</li>
      <li>确认已取/已收到按钮是当收到或已取到客户物品时的操作，点击后状态即为定损中</li>
    </ul>
  </div>
  <div class="flexigrid">
    <div class="mDiv">
      <div class="ftitle">
        <h3>订单列表</h3>
        <h5>(共<?php echo $page->totalRows; ?>条记录)</h5>
      </div>
      <div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>
	  <form class="navbar-form form-inline"  method="post" action="<?php echo U('Admin/order/export_order'); ?>"  name="search-form2" id="search-form2">  
	  		<input type="hidden" name="order_by" value="order_id">
            <input type="hidden" name="sort" value="desc">
            <input type="hidden" name="user_id" value="<?php echo \think\Request::instance()->param('user_id'); ?>">
            <input type="hidden" name="order_ids" value="">
            <!--用于查看结算统计 包含了哪些订单-->
            <input type="hidden" value="<?php echo $_GET['order_statis_id']; ?>" name="order_statis_id" />
      <div class="sDiv">
        <div class="sDiv2">
        	<input type="text" size="30" id="add_time_begin" name="add_time_begin" value="" class="qsbox"  placeholder="下单开始时间">
        </div>
        <div class="sDiv2">
        	<input type="text" size="30" id="add_time_end" name="add_time_end" value="" class="qsbox"  placeholder="下单结束时间">
        </div>
          <?php if($role_id > 16 || $role_id < 16): ?>
              <div class="sDiv2">
                  <select name="ywy_id" class="select sDiv3" style="margin-right:5px;margin-left:5px">
                      <option value="">业务员</option>
                      <?php if(is_array($admin) || $admin instanceof \think\Collection || $admin instanceof \think\Paginator): $k = 0; $__LIST__ = $admin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>
                          <option value="<?php echo $v['admin_id']; ?>"><?php echo $v['user_name']; ?></option>
                      <?php endforeach; endif; else: echo "" ;endif; ?>
                  </select>
              </div>
          <?php endif; ?>
          <div class="sDiv2">
              <select name="order_status" class="select sDiv3" style="margin-right:5px;margin-left:5px">
                  <option value="">订单状态</option>
                  <?php if(is_array($order_status) || $order_status instanceof \think\Collection || $order_status instanceof \think\Paginator): $k = 0; $__LIST__ = $order_status;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>
                      <option value="<?php echo $k-1; ?>"><?php echo $v; ?></option>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
          </div>
          <div class="sDiv2">
              <select name="pay_status" class="select sDiv3" style="margin-right:5px;margin-left:5px">
                  <option value="">支付状态</option>
                  <?php if(is_array($pay_status) || $pay_status instanceof \think\Collection || $pay_status instanceof \think\Paginator): $k = 0; $__LIST__ = $pay_status;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>
                      <option value="<?php echo $k-1; ?>"><?php echo $v; ?></option>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
          </div>
         <div class="sDiv2">	 
          <input type="text" size="30" name="keywords" class="qsbox" placeholder="搜索相关数据...">
        </div>
        <div class="sDiv2">	 
          <input type="button" onclick="ajax_get_table('search-form2',1)"  class="btn" value="搜索">
        </div>
      </div>
     </form>
    </div>
    <div class="hDiv">
      <div class="hDivBox" id="ajax_return">
        <table cellspacing="0" cellpadding="0">
          <thead>
	        	<tr>
	              <th axis="col0">
	                <div style="width: 24px;"><i class="ico-check"></i></div>
	              </th>
                    <th align="center" abbr="order_id" axis="col3" class="">
                        <div style="text-align: center; width: 40px;" class="">订单ID</div>
                    </th>
	              <th align="center" abbr="order_sn" axis="col3" class="">
	                <div style="text-align: center; width: 140px;" class="">订单编号</div>
	              </th>
	              <th align="center" abbr="consignee" axis="col4" class="">
	                <div style="text-align: center; width: 100px;" class="">下单人</div>
	              </th>
                <th align="center" abbr="consignee" axis="col4" class="">
                  <div style="text-align: center; width: 150px;" class="">货物名称</div>
                </th>
                <th align="center" abbr="consignee" axis="col4" class="">
                    <div style="text-align: center; width: 100px;" class="">联系方式</div>
                </th>
                <th align="center" abbr="article_time" axis="col6" class="">
                    <div style="text-align: center; width: 100px;" class="">订单状态</div>
                </th>
	              <th align="center" abbr="article_show" axis="col5" class="">
	                <div style="text-align: center; width: 200px;" class="">地址</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 60px;" class="">目前进度</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 80px;" class="">客服人员</div>
	              </th>
	            <!--   <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 60px;" class="">应付款金额</div>
	              </th> -->
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 120px;" class="">入库时间</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 120px;" class="">出库时间</div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 120px;" class="">下单时间</div>
	              </th>
	              <th align="left" axis="col1" class="handle">
	                <div style="text-align: left; width: 150px;">操作</div>
	              </th>
	            </tr>
	          </thead>
        </table>
      </div>
    </div>
    <div class="tDiv">
      <div class="tDiv2">
        <div class="fbutton"> 
        	<a href="javascript:exportReport()">
	          	<div class="add" title="选定行数据导出excel文件,如果不选中行，将导出列表所有数据">
	            	<span><i class="fa fa-plus"></i>导出数据</span>
	          	</div>
          	</a> 
          </div>
          <div class="fbutton">
              <a href="javascript:void(0);" onclick="allFp();">
                  <div class="add" title="批量分配">
                        <span><i class="fa fa-plus"></i>批量分配</span>
                  </div>
              </a>
          </div>
          <div class="fbutton">
        	<a href="/index.php?m=Admin&c=Order&a=add_order">
	          	<div class="add" title="添加订单">
	            	<span><i class="fa fa-plus"></i>添加订单</span>
	          	</div>
          	</a>
          </div>
      </div>
      <div style="clear:both"></div>
    </div>
    <div class="bDiv" style="height: auto;">
      <div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
        
      </div>
      <div class="iDiv" style="display: none;"></div>
    </div>
    <!--分页位置--> 
   	</div>
</div>
<script type="text/javascript">

	 
    $(document).ready(function(){	
	   
     	$('#add_time_begin').layDate(); 
     	$('#add_time_end').layDate();
     	
		// 点击刷新数据
		$('.fa-refresh').click(function(){
			location.href = location.href;
		});
		
		ajax_get_table('search-form2',1);
		
		$('.ico-check ' , '.hDivBox').click(function(){
			$('tr' ,'.hDivBox').toggleClass('trSelected' , function(index,currentclass){
	    		var hasClass = $(this).hasClass('trSelected');
	    		$('tr' , '#flexigrid').each(function(){
	    			if(hasClass){
	    				$(this).addClass('trSelected');
	    			}else{
	    				$(this).removeClass('trSelected');
	    			}
	    		});  
	    	});
		});
		 
	});
    
    
  	//ajax 抓取页面
    function ajax_get_table(tab,page){
        cur_page = page; //当前页面 保存为全局变量
            $.ajax({
                type : "POST",
                url:"/index.php/Admin/order/ajaxindex/p/"+page,//+tab,
                data : $('#'+tab).serialize(),// 你的formid
                success: function(data){
                    $("#flexigrid").html('');
                    $("#flexigrid").append(data);
                    
                	// 表格行点击选中切换
            	    $('#flexigrid > table>tbody >tr').click(function(){
            		    $(this).toggleClass('trSelected');
            		});
                	 
                }
            });
    }
	
 // 点击排序
    function sort(field){
        $("input[name='order_by']").val(field);
        var v = $("input[name='sort']").val() == 'desc' ? 'asc' : 'desc';
        $("input[name='sort']").val(v);
        ajax_get_table('search-form2',cur_page);
    }
	
	function exportReport(){
        var selected_ids = '';
        $('.trSelected' , '#flexigrid').each(function(i){
            selected_ids += $(this).data('order-id')+',';
        });
        if(selected_ids != ''){
            $('input[name="order_ids"]').val(selected_ids.substring(0,selected_ids.length-1));
        }
		$('#search-form2').submit();
	}
    function allFp(){
        var selected_ids = '';
        $('.trSelected' , '#flexigrid').each(function(i){
            selected_ids += $(this).data('order-id')+',';
        });
        if(selected_ids==""){
            alert('少侠，选一个吧！');
        }else{
            $('#order_id').val(selected_ids);
            $('#fenpei').show();
        }
    }
	
	 
</script>
</body>
</html>