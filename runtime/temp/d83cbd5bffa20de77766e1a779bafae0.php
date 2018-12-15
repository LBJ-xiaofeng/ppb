<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:56:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\member.html";i:1537499183;}*/ ?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset='UTF-8'>
		<meta name='viewport' content='width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no' />
		<title>皮皮班</title>
		
		<link href='/ppb/css/mui.css' rel='stylesheet' />
		<link rel='stylesheet' type='text/css' href='/ppb/css/member.css' />
		<link rel='stylesheet' type='text/css' href='/ppb/css/aui.css'/>
		<link rel="stylesheet" type="text/css" href="/ppb/css/style.css">
		
		<style type='text/css'>
body,html,div{
            background-color: #fff;
        }
    </style>
	</head>

	<body>
		<div class='mui-content'>
			<div class='me'>
				
			</div>
			<div class='youhui'>
				<a href="<?php echo U('ppb/ppb/youhui1'); ?>"><div> <img src='/ppb/image/geren (4).jpg' alt='' /><span>我的钱包</span></div></a>
				<a href="<?php echo U('ppb/ppb/dizhi'); ?>"><div><img src='/ppb/image/geren (5).jpg' alt='' /><span>常用地址</span></div></a>
				<a class="toshare" href="<?php echo U('ppb/user/jssuser'); ?>"><div><img src='/ppb/image/geren (6).jpg' alt='' /><span>推荐有奖</span></div></a>
				<a href='javascript:void(0)' class="kefu"><div><img src='/ppb/image/geren (7).jpg' alt='' /><span>客服电话</span></div></a>
				<a href="<?php echo U('ppb/ppb/changjian'); ?>"><div><img src='/ppb/image/geren (8).jpg' alt='' /><span>常见问题</span></div></a>
				<a href="<?php echo U('ppb/ppb/shezhi'); ?>"><div><img src='/ppb/image/geren (1).jpg' alt='' /><span>设置</span></div></a>
				
			</div>
		</div>
		<p style="margin-bottom: 100px"></p>
        <footer class='aui-bar aui-bar-tab aui-bar-tab-cl aui-border-t gddw' id='footer'>
            
            <a href="<?php echo U('ppb/ppb/index'); ?>" class='aui-bar-tab-item ' tapmode>
                <i><img src="/ppb/image/mine(2).png"></i>
                
            </a>
            
            <a class='aui-bar-tab-item' tapmode href="<?php echo U('ppb/ppb/daifahuo'); ?>">
                
                <i><img src="/ppb/image/mine (6).jpg"></i>
            </a>
                <a class='aui-bar-tab-item' tapmode href="<?php echo U('ppb/ppb/member'); ?>">
                
                <i><img src="/ppb/image/mine(4).jpg"></i>
            </a>
            
        
        </footer>
        <div class="mask">
        	<div class="content">
        		<p>客服电话</p>
        		<a href="tel:13691255667" class="kfphone"><span class="kfmobile"></span><span><img src="/ppb/image/tel.jpg"></span></a>
        		<span class="close">关闭</span>
        	</div>
        </div>
	</body>
	 <script src="/ppb/js/jquery.min.js"></script>
<script type='text/javascript'>
	$(".close").click(function(){
		$(".mask").hide();
	})
	$(".kefu").click(function(){
		$(".mask").show();
	})
	if(localStorage.getItem('user_id')==null){
		alert('请先登录');
	window.location.href="<?php echo U('ppb/ppb/login'); ?>";
}
$.ajax({
		type: 'post', 
		url: 'http://ppb.dhxdrawing.top/index.php/ppb/user/getinfo', //目标地址
		dataType: 'JSON', //数据格式:JSON
		data: {user_id:localStorage.getItem('user_id')},
		success: function(data) {
			 console.log(data);
				$(".me").html(`
						<li><a href="<?php echo U('ppb/ppb/xiugaixinxi'); ?>"><img src="${data.data.head_pic}"></a></li>
				<li>
					<p>${data.data.nickname}</p>
					<p>${data.data.district}</p>
				</li>
					`)
				
			$(".kfphone").attr('href','tel:'+data.data.kf_phone);
			$(".kfmobile").html(data.data.kf_phone);
			$(".toshare").attr('href',"<?php echo U('ppb/user/jssuser'); ?>?user_id="+data.data.user_id);
		}
	})
</script>
</html>