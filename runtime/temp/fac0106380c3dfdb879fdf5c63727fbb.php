<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:49:"./application/admin/view2/goods\categoryEdit.html";i:1541388608;s:44:"./application/admin/view2/public\layout.html";i:1528854675;}*/ ?>
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
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div id="list">
        <div class="fixed-bar">
            <div class="item-title">
                <div class="subject">
                    <h3>设置</h3>
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
                <li>第二级数据需要上传图片</li>
                <li>第三级数据为样例列表的分类</li>
            </ul>
        </div>
    <div id="add_region">
        <div class="page" style="padding-top: 0px;">
            <div class="fixed-bar">
                <div class="item-title"><a class="back" href="<?php echo U('Admin/Goods/categoryLists',array('parent_id'=>$data['parent_id'])); ?>" title="返回上级"><i class="fa fa-arrow-circle-o-left"></i></a>
                    <div class="subject">
                        <h3>设置 - 新增</h3>
                        <h5>新增与编辑</h5>
                    </div>
                </div>
            </div>
            <form id="add_region_form" enctype="multipart/form-data" method="post" action="<?php echo U('Goods/categoryEdit'); ?>">
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                <div class="ncap-form-default">
                    <dl class="row">
                        <dt class="tit">
                            <label for="mobile_name"><em>*</em>分类名</label>
                        </dt>
                        <dd class="opt">
                            <input id="mobile_name" name="mobile_name" value="<?php echo $data['mobile_name']; ?>" maxlength="20" class="input-txt" type="text">
                            <span class="err"></span>
                            <p class="notic">请认真填写分类，设定后将直接显示在页面，请谨慎操作。</p>
                        </dd>
                    </dl>
                    <dl class="row">
                        <dt class="tit">
                            <label for="image">小图标</label>
                        </dt>
                        <dd class="opt">
                            <div class="fileupload-new thumbnail">
                            <?php if($data['image']): ?>
                                <img class="img" style="width: 150px; height: 150px;cursor:pointer;background:#333;" src="<?php echo $data['image']; ?>" title="点击选择" />
                            <?php else: ?>
                                <img class="img" style="width: 150px; height: 150px;cursor:pointer;background:#333;" src="/ppb/image/logo.png" title="点击选择" />
                            <?php endif; ?>
                                </div>
                            <div><input class="uplode" name="image" id="btn_file" style="display: none" type="file"></div>
                            <p class="notic">像素700*400左右</p>
                        </dd>
                    </dl>
                    <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="$('#add_region_form').submit();">确认提交</a></div>
                </div>
            </form>
        </div>
<script>
    $(document).ready(function(){
        // 表格行点击选中切换
        $('#flexigrid > table>tbody >tr').click(function(){
            $(this).toggleClass('trSelected');
        });

        // 点击刷新数据
        $('.fa-refresh').click(function(){
            location.href = location.href;
        });

        //返回上级
        function add_region()
    {
        window.location.href = "<?php echo U('Goods/categoryLists',array('parent_id'=>$data['parent_id'])); ?>";
    }

    });
    $('.img').click(function(){
        document.getElementById("btn_file").click();
    })
    $(function () {

        $(".uplode").on("change",function(){
            var objUrl = getObjectURL(this.files[0]) ; //获取图片的路径，该路径不是图片在本地的路径
            if (objUrl) {
                $(".img").attr("src", objUrl) ; //将图片路径存入src中，显示出图片
            }
        });
    });
    function getObjectURL(file) {
        var url = null ;
        if (window.createObjectURL!=undefined) { // basic
            url = window.createObjectURL(file) ;
        } else if (window.URL!=undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file) ;
        } else if (window.webkitURL!=undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file) ;
        }
        return url ;
    }
</script>
</body>
</html>