<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:55:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\login.html";i:1537349635;}*/ ?>
<!DOCTYPE html>
<html class="ui-page-login">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link rel="stylesheet" type="text/css" href="/ppb/css/index.css"/>
		<style type="text/css">
			.gongjiang{
				font-size: 10px;
				color: #fff;
				display: inline-block;
				width: 30%;
			}
		</style>
	</head>
	<body>
	<div class="login_div1">
		<a href="<?php echo U('ppb/ppb/login2'); ?>"><img src="/ppb/image/logo.png"></a>
	</div>
	<div class="boos-boos">
	<div class="boos" style="height: 600px;">
		 <div class="sjh">
		 	<span><img src="/ppb/image/tel.png"></span>
		 	<input type="text" name="tel" id="" placeholder="请输入手机号" class="tel" />
		 	
		 </div>
		 <div class="sjh">
		 	<span><img src="/ppb/image/pwd.png"></span>
		 	<input type="password" name="pwd" id="" placeholder="请输入登录密码"  class="pwd"/>
		 	
		 	
		 </div>
		<div class="sjh" style="border:none;">
		  	<a href="<?php echo U('ppb/ppb/zhuce'); ?>" style="color: #fff;font-size: 12px;text-decoration: none;float: left;">没有账号，立即注册</a>
		  	
		  	<a href="<?php echo U('ppb/ppb/wjmm'); ?>" style="color: #fff;font-size: 12px;text-decoration: none;float: right;">忘记密码</a>
		  </div>
		 
		 <div class="but">
		 	<button class="btn">登录</button>
		 </div>
		 
		
		  <!--<div  class="qita2">-->
<!--<span><img src="/ppb/image/fenge.png"  style="display: inline-block;width: 80%"></span>-->
		 <!--</div>-->
		 <!--<div class="tubiao-2">-->
		 	<!--<img src="/ppb/image/wei.png" class="sanfang">-->
		 <!--</div>-->
	</div>
	</body>
		<script src="/ppb/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	$('.but').click(function(){
		var pwd=$('.pwd').val();
		var tel=$('.tel').val();
       $.ajax({
		type: 'post', //用POST方式传输
		url: 'http://ppb.dhxdrawing.top/index.php/ppb/user/login', //目标地址
		dataType: 'JSON', //数据格式:JSON
		data: {mobile:tel,pwd:pwd},
		success: function(data) {
           console.log(data);
           if(data.status==1){
           	localStorage.setItem('user_id',data.data.user_id);
           		alert("登录成功")
           	window.location.href="<?php echo U('ppb/ppb/index'); ?>";
           }else{
           	alert(data.msg);
           }
		},
		error: function(data) {
				alert('网络错误');
		}
		
	});
	})
</script>
</html>