<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:45:"./application/admin/view2/wechat\setting.html";i:1537431867;s:44:"./application/admin/view2/public\layout.html";i:1528854675;}*/ ?>
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
</style>
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.back();" title="微信支付配置"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>微信配置</h3>
                <h5>配置微信 mchid、apiKey、appid、appsecret要与微信商户平台、公众开放平台信息一致</h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" method="post" id="handlepost" action="" enctype="multipart/form-data">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>MCHID</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="mchid" size="30" value="<?php echo $wechat['mchid']; ?>" class="input-txt">
                    <span class="err"></span>
                    <p class="notic">微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>APIKEY</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="apikey" value="<?php echo $wechat['apikey']; ?>" class="input-txt">
                    <span class="err"></span>
                    <p class="notic">微信商户平台->帐户设置-安全设置-API安全-API密钥-设置API密钥</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>APPID</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="appid" size="30" value="<?php echo $wechat['appid']; ?>" class="input-txt">
                    <span class="err"></span>
                    <p class="notic">微信支付申请对应的公众号的APPID</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>APPSECRET</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="appsecret" value="<?php echo $wechat['appsecret']; ?>" class="input-txt">
                    <span class="err"></span>
                    <p class="notic">微信支付申请对应的公众号APPID生成的AppSecret</p>
                </dd>
            </dl>
              <dl class="row">
                <dt class="tit">
                    <label><em>*</em>微信邀请标题</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="wxname" value="<?php echo $wechat['wxname']; ?>" class="input-txt">
                    <span class="err"></span>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>微信邀请描述</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="share_ticket" value="<?php echo $wechat['share_ticket']; ?>" class="input-txt">
                    <span class="err"></span>
                </dd>
            </dl>
            <!--<dl class="row">-->
                <!--<dt class="tit">-->
                    <!--<label><em>*</em>微信分享跳转地址</label>-->
                <!--</dt>-->
                <!--<dd class="opt">-->
                    <!--<input type="text" name="qr" value="<?php echo $wechat['qr']; ?>" class="input-txt">-->
                    <!--<span class="err"></span>-->
                    <!--<p class="notic">格式:http://您的链接/，如：http://www.baidu.com/</p>-->
                <!--</dd>-->
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>微信邀请图标</label>
                </dt>
                <dd class="opt">
                        <img src="<?php echo $wechat['headerpic']; ?>" style="width: 120px;height: 100px;" id="saveImg">
                        <input type="file" id="file" name="headerpic" value="<?php echo $wechat['headerpic']; ?>" class="input-txt" style="display: none">
                        <span class="err"></span>

                </dd>
            </dl>
            <!--<dl class="row">-->
                <!--<dt class="tit">-->
                    <!--<label><em>*</em>公众号二维码</label>-->
                <!--</dt>-->
                <!--<dd class="opt">-->
                    <!--<img src="<?php echo $wechat['w_token']; ?>" style="width: 120px;height: 100px;" id="saveImgs">-->
                    <!--<input type="file" id="files" name="w_token" value="<?php echo $wechat['w_token']; ?>" class="input-txt" style="display: none">-->
                    <!--<span class="err"></span>-->

                <!--</dd>-->
            <!--</dl>-->
            <input type="hidden" name="id" value="<?php echo $wechat['id']; ?>">
            <div class="bot"><a href="JavaScript:void(0);" onClick="formSubmit()" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
        </div>
    </form>
</div>
<script>
    $('#saveImg').click(function(){
        $('#file').click();
    })
    $('#saveImgs').click(function(){
        $('#files').click();
    })
</script>
<script type="text/javascript">
    $(document).ready(function(){

        $("#handlepost").validate({
            debug: false, //调试模式取消submit的默认提交功能
            focusInvalid: false, //当为false时，验证无效时，没有焦点响应
            onkeyup: false,
            submitHandler: function(form){   //表单提交句柄,为一回调函数，带一个参数：form
                form.submit();   //提交表单
            },
            ignore:":button", //不验证的元素
            rules:{
                w_token:{
                    required:true
                },
                wxname:{
                    required:true
                },
                appsecret:{
                    required:true
                },
                wxid:{
                    required:true
                },
                weixin:{
                    required:true
                },
                appid:{
                    required:true
                },
                appsecret:{
                    required:true
                }
            },
            messages:{
                mchid:{
                    required:"请填写mchid"
                },
                apikey:{
                    required:"请填写apikey"
                },
                appid:{
                    required:"请填写appid"
                },
                appsecret:{
                    required:"请填写AppSecret"
                }
            }
        });


    });
    function formSubmit(){
        $("#handlepost").submit();
    }

    function img_call_back(fileurl_tmp)
    {
        $("#headerpic").val(fileurl_tmp);
        $("#img_a").attr('href', fileurl_tmp);
        $("#img_i").attr('onmouseover', "layer.tips('<img src="+fileurl_tmp+">',this,{tips: [1, '#fff']});");
    }
    function img_call_back2(fileurl_tmp)
    {
        $("#qr").val(fileurl_tmp);
        $("#img_a2").attr('href', fileurl_tmp);
        $("#img_i2").attr('onmouseover', "layer.tips('<img src="+fileurl_tmp+">',this,{tips: [1, '#fff']});");
    }
</script>
</body>
</html>