<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:56:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\shezhi.html";i:1537345649;}*/ ?>
<!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>设置</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link rel="stylesheet" type="text/css" href="/ppb/css/mui.css"/>
		<script src="/ppb/js/mui.min.js"></script>
		<script type="text/javascript">
			mui.init()
		</script>
		<style type="text/css">
			.td{
				width: 100%;
				margin-top: 30px;
				
			}
			.tcdl{
			width: 70%;
			margin: 0 auto;
			}
			.tcdl button{
				width: 100%;
				border-radius: 5px;
				    width: 100%;
    border-radius: 5px;
    background-color: #00BFFF;
    color: white
			}
			.tuichu{
				    position: fixed;
    bottom: 0;
    width: 100%;
    background: #ffbfc6;
    color: #fff;
    font-size: 19px;
    padding: 7px 0px;
    border: none;
			}
			ul a{
				font-size: 17px;
			}
			body{
				background-color: #fff;
			}
			.zhezhiul li a{
				font-size: 15px;
			}
		</style>
	</head>

	<body>
	<header class="mui-bar mui-bar-nav" >
			<a class="mui-icon mui-icon-left-nav mui-pull-left" href='javascript:history.go(-1)' style="color: black;"></a>
			<a class="mui-icon mui-icon-reply mui-pull-right"></a>
			<h1 class="mui-title" >设置</h1>
	  </header>
	<ul class="mui-table-view zhezhiul" style="font-size: 14px;margin-top: 45px;">
  <li class="mui-table-view-cell qchc">
    <a  href="<?php echo U('ppb/ppb/xieyi'); ?>">
       用户协议
    </a>
  </li>
 
  <li class="mui-table-view-cell">
    <a  href="<?php echo U('ppb/ppb/about'); ?>">
      关于我们
    </a>
  </li>
   <li class="mui-table-view-cell">
    <a href="<?php echo U('ppb/ppb/yjfk'); ?>">
      意见反馈
    </a>
  </li>
   
</ul>

<button class="tuichu" style="font-size: 15px;">退出登录</button>
	</body>
				<script type="text/javascript" src="/ppb/js/jquery.min.js"></script>

<script type="text/javascript">
	
	$(".tuichu").click(function(){
		localStorage.clear();
		window.location.href="<?php echo U('ppb/ppb/index'); ?>";
	})
	$(".qchc").click(function(){
		localStorage.clear();
		window.location.href="<?php echo U('ppb/ppb/index'); ?>";
	})
	
</script>
</html>