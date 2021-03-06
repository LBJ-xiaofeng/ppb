<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:46:"./application/admin/view2/goods\goodsList.html";i:1536113313;s:44:"./application/admin/view2/public\layout.html";i:1528854675;}*/ ?>
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
    span.type-virtual {
        background-color: #3598DC;
        line-height: 16px;
        color: #FFF;
        display: inline-block;
        height: 16px;
        padding: 1px 4px;
        margin-right: 2px;
        box-shadow: inset 1px 1px 0 rgba(255,255,255,0.25);
        cursor: default;
    }
     body{background: #f5f5f5}
    .bigimg{width:600px;position: fixed;left: 0;top: 0; right: 0;bottom: 0;margin:auto;display: none;z-index:9999;border: 10px solid #fff;}
    .mask{position: fixed;left: 0;top: 0; right: 0;bottom: 0;background-color: #000;opacity:0.5;filter: Alpha(opacity=50);z-index: 98;transition:all 1s;display: none}
    .bigbox{width:840px;background: #fff;border:1px solid #ededed;margin:0 auto;border-radius: 10px;overflow: hidden;padding:10px;}
    .bigbox>.imgbox{width:400px;height:250px;float:left;border-radius:5px;overflow: hidden;margin: 0 10px 10px 10px;}
    .bigbox>.imgbox>img{width:100%;}
    .imgbox:hover{cursor:zoom-in}
    .mask:hover{cursor:zoom-out}
    .mask>img{position: fixed;right:10px;top: 10px;width: 60px;}
    .mask>img:hover{cursor:pointer}
</style>
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>样例管理</h3>
        <h5>所有样例索引及管理</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div id="explanation" class="explanation" style="color: rgb(44, 188, 163); background-color: rgb(237, 251, 248); width: 99%; height: 100%;">
    <div id="checkZoom" class="title"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span title="收起提示" id="explanationZoom" style="display: block;"></span>
    </div>
    <ul>
      <li>添加、编辑样例时请选对所属类</li>
    </ul>
  </div>
  <div class="flexigrid">
    <div class="mDiv">
      <div class="ftitle">
        <h3>样例列表</h3>
        <h5></h5>
      </div>
        <a href=""><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></a>
	<form action="" id="search-form2" class="navbar-form form-inline" method="post" onSubmit="return false">
      <div class="sDiv">
        <div class="sDiv2">
            <select name="cat_id" id="cat_id" class="select">
                <option value="">一级分类</option>
                <?php if(is_array($cat_list) || $cat_list instanceof \think\Collection || $cat_list instanceof \think\Paginator): if( count($cat_list)==0 ) : echo "" ;else: foreach($cat_list as $k=>$vo): if(is_array($vo['child']) || $vo['child'] instanceof \think\Collection || $vo['child'] instanceof \think\Paginator): if( count($vo['child'])==0 ) : echo "" ;else: foreach($vo['child'] as $k=>$v): ?>
                        <option value="<?php echo $v['id']; ?>"><?php echo $v['mobile_name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
            </select>
          <select name="is_on_sale" id="is_on_sale" class="select">
            <option value="">全部</option>                  
            <option value="1">显示</option>
            <option value="0">隐藏</option>
          </select>

            <!--排序规则-->
            <input type="hidden" name="orderby1" value="goods_id" />
            <input type="hidden" name="orderby2" value="desc" />
          <input type="text" size="30" name="key_word" class="qsbox" placeholder="搜索词...">
          <input type="button" onClick="ajax_get_table('search-form2',1)" class="btn" value="搜索">
        </div>
      </div>
     </form>
    </div>
    <div class="hDiv">
      <div class="hDivBox">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <th class="sign" axis="col6" onclick="checkAllSign(this)">
                <div style="width: 24px;"><i class="ico-check"></i></div>
              </th>
              <th align="left" abbr="article_title" axis="col6" class="">
                <div style="text-align: left; width:65px;" class="">操作</div>
              </th>              
              <th align="left" abbr="article_title" axis="col6" class="">
                <div style="text-align: center; width:50px;" class="" onClick="sort('goods_id');">id</div>
              </th>
              <th align="left" abbr="ac_id" axis="col4" class="">
                <div style="text-align: center; width: 100px;" class="" onClick="sort('goods_name');">名称</div>
              </th>
              <th align="center" abbr="article_show" axis="col0" class="">
                <div style="text-align: center; width: 160px;" class="" onClick="sort('goods_sn');">预览</div>
              </th>
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 50px;" class="" onClick="sort('shop_price');">金额</div>
              </th>
                <th align="center" abbr="article_time" axis="col6" class="">
                    <div style="text-align: center; width: 100px;" class="" onClick="sort('goods_remark');">简述</div>
                </th>
                <th align="center" abbr="article_time" axis="col6" class="">
                    <div style="text-align: center; width: 100px;" class="" onClick="sort('cat_id');">所属一级分类</div>
                </th>
                <th align="center" abbr="article_time" axis="col6" class="">
                    <div style="text-align: center; width: 100px;" class="" onClick="sort('extend_cat_id');">所属二级分类</div>
                </th>
                <th align="center" abbr="article_time" axis="col6" class="">
                    <div style="text-align: center; width: 200px;" class="" onClick="sort('on_time');">添加时间</div>
                </th>
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 100px;" class="" onClick="sort('is_on_sale');">显示/隐藏</div>
              </th>
              <!--<th align="center" abbr="article_time" axis="col6" class="">-->
                <!--<div style="text-align: center; width: 50px;" class="" onClick="sort('store_count');">库存</div>-->
              <!--</th>-->
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 50px;" class="" onClick="sort('sort');">排序</div>
              </th>
              <th style="width:100%" axis="col7">
                <div></div>
              </th>
            </tr>
          </thead>
        </table>
      </div>
    </div>    
    <div class="tDiv">
      <div class="tDiv2">
        <div class="fbutton">       
          <a href="<?php echo U('Admin/goods/addEditGoods'); ?>">
          <div class="add" title="添加样例">
            <span><i class="fa fa-plus"></i>添加样例</span>
          </div>
          </a>          
          </div> 
        <!--<div class="fbutton">-->
            <!--<a href="<?php echo U('Admin/Goods/initGoodsSearchWord'); ?>">-->
                <!--<div class="add" title="初始化商品搜索关键词">-->
                    <!--<span><i class="fa fa-plus"></i>初始化商品搜索关键词</span>-->
                <!--</div>-->
            <!--</a>-->
        <!--</div>-->
        <div class="fbutton">
          <a href="javascript:;" onclick="publicHandleAll('del')">
              <div class="add" title="批量删除">
                  <span>批量删除</span>
              </div>
          </a>
      </div>
      </div>
      <div style="clear:both"></div>
    </div>
    <div class="bDiv" style="height: auto;">
     <!--ajax 返回 --> 
      <div id="flexigrid" cellpadding="0" cellspacing="0" border="0" data-url="<?php echo U('admin/goods/delGoods'); ?>"></div>
    </div>

     </div>
</div>
<script>
    $(document).ready(function(){
		// 刷选条件 鼠标 移动进去 移出 样式
		$(".hDivBox > table > thead > tr > th").mousemove(function(){
			$(this).addClass('thOver');
		}).mouseout(function(){
			$(this).removeClass('thOver');
		});

        // 表格行点击选中切换
        $(document).on('click','#flexigrid > table>tbody >tr',function(){
            $(this).toggleClass('trSelected');
            var checked = $(this).hasClass('trSelected');
            $(this).find('input[type="checkbox"]').attr('checked',checked);
        });
	});

    $(document).ready(function () {
        // ajax 加载商品列表
        ajax_get_table('search-form2', 1);

    });

    // ajax 抓取页面 form 为表单id  page 为当前第几页
    function ajax_get_table(form, page) {
        cur_page = page; //当前页面 保存为全局变量
        $.ajax({
            type: "POST",
            url: "/index.php?m=Admin&c=goods&a=ajaxGoodsList&p=" + page,//+tab,
            data: $('#' + form).serialize(),// 你的formid
            success: function (data) {
                $("#flexigrid").html('');
                $("#flexigrid").append(data);
            }
        });
    }
	
        // 点击排序
        function sort(field)
        {
           $("input[name='orderby1']").val(field);
           var v = $("input[name='orderby2']").val() == 'desc' ? 'asc' : 'desc';             
           $("input[name='orderby2']").val(v);
           ajax_get_table('search-form2',cur_page);
        }
</script>
</body>
</html>