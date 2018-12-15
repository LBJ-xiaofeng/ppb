<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:58:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\gj_index.html";i:1540948693;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<title>工匠端</title>
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no' />
	<style type="text/css">
		body,div,p,ul,li{
			padding: 0;
			margin: 0;
			list-style: none;
		}
		.gj_div1{
			height: 142px;
			background-color: #FF9D14;
			position: relative;
		}
		.gj_div1 p{
			    text-align: center;
			    color: #fff;
			    padding-top: 24px;
		}
		.gj_div2{
			position: absolute;
			top: 69px;
			left: 5%;
			width: 90%;
			height: 94px;
			background-color: white;
			padding-top: 50px;
			border-radius: 5px;
		}
		.gj_div2 ul{
			overflow: hidden;
		}
		.gj_div2 ul li{
			float: left;
			/*width: 30%;*/
			width: 47%;
			text-align: center;
			margin-left: 3%;
		}
		.gj_div2 ul li img{
			display: inline-block;
			width: 37%;
		}
		.gj_div2 ul li p{
			font-size: 14px;
		}
		.imgs img{
			display: inline-block;
			width: 100%;
		}
		.imgs{
			margin-top: 104px;
		}
		a{
			color: black;
			text-decoration : none ;
		}
	</style>
</head>
<body>
<div class="gj_div1">
	<p>皮皮班工匠端</p>
</div>
<div class="gj_div2">
	<ul>
		<li><a onclick="clickddgl()" href="javascript:void(0);"><img src="/ppb/image/gj (2).png"><p>订单管理</p></a></li>
		<li><a onclick="clickddgllist()" href="javascript:void(0);"><img src="/ppb/image/gj (3).png"><p>进度管理</p></a></li>
		<li style="display: none;"><a onclick="clickzjy()" href="javascript:void(0);"><img src="/ppb/image/gj (1).png"><p>质检员</p></a></li>
	</ul>
</div>
<div class="imgs">
	<a href="<?php echo U('ppb/ppb/gj_flgl'); ?>"><img src="/ppb/image/banner (1).jpg"></a>
	<a href="<?php echo U('ppb/ppb/gj_yjgl'); ?>"><img src="/ppb/image/banner (2).jpg"></a>
	<a href="<?php echo U('ppb/ppb/gj_gzgl'); ?>"><img src="/ppb/image/banner (1).png"></a>
</div>
</body>
<script src="/ppb/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

	if (localStorage.getItem('admin_id')) {
		
	} else{
		alert("请先登录")
		location.href="<?php echo U('ppb/ppb/login2'); ?>";
	}
	function clickddgl(){
		if (localStorage.getItem('role_id') ==16 || localStorage.getItem('role_id') ==1) {
			location.href="<?php echo U('ppb/ppb/gj_ddgl'); ?>";
		} else{
			alert('您无权限访问');
		}
	}
	function clickddgllist(){
		if (localStorage.getItem('role_id') ==16 || localStorage.getItem('role_id') ==1) {
			location.href="<?php echo U('ppb/ppb/gj_jdgllist'); ?>";
		} else{
			alert('您无权限访问');
		}
	}
	function clickzjy(){
		if (localStorage.getItem('role_id') ==17 || localStorage.getItem('role_id') ==1) {
			location.href="<?php echo U('ppb/ppb/gj_zjy'); ?>";
		} else{
			alert('您无权限访问');
		}
	}
</script>
</html>