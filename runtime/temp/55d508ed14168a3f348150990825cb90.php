<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:54:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\yycg.html";i:1537349688;}*/ ?>
<!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>预约成功</title>
		<meta name="viewport" content="width=device-width, user-scalable=.mian-ul1 li, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
		<link href="/ppb/css/mui.min.css" rel="stylesheet" />
		<style type="text/css">
			.total{
					margin-top: 50px;
			}
			.total-xinxi{
				
				border-bottom: 2px solid gainsboro;
				padding: 7px;
			}
			.total-xinxi ul{
				list-style:none;
				font-size: 16px;
				
				
			}
			.total-xinxi ul li{
				font-size: 13px;
				height: 30px;
				color: gray;
			}
			.total-xinxi ul li:first-child{
				margin-top: 15px;
				
				}
				.total-wupin{
				
							padding: 7px;
			}
			.total-wupin ul{
				list-style:none;
				font-size: 16px;
				
				
			}
			a{
				color: #121212;
			}
			.mui-bar .mui-btn-link{
				color: #121212;
			}
			.total-wupin ul li{
				font-size: 13px;
				height: 30px;
				color: gray;
			}
			.total-wupin ul li:first-child{
				margin-top: 15px;
				
				}
				.wupin-button{
					width: 70%;
					height: 60px;
					margin: 99px auto 0 auto;
				}
			.wupin-button button{
				background-color: #ffbfc6;
				color: #fff;
				border: 0;
				width: 45%;
				height: 30px;
				border-radius: 30px;
			}
			.wupin-button button:first-child{
				float: left;
			}	
			.wupin-button button:last-child{
				float: right;
				margin-left: 11px;
			}
			
		</style>
	</head>

	<body>
			<header class="mui-bar mui-bar-nav">
				  
				  <button class="mui-btn mui-btn-link mui-btn-nav mui-pull-right" style="font-size: 27px; margin-top: -5px">
				  
				   <span class="mui-icon mui-icon-right-nav"></span>
				  </button>
				  <h1 class="mui-title">预约成功</h1>
			</header>
			 <div class="total">
				<div class="total-xinxi">
					<ul>基本信息
					
						<li class="name">姓名：张三</li>
						<li class="mobile">手机：123456789123</li>
						<li>数量：1件</li>
						<li class="province">地址：北京北京北京北京</li>
						
						<!--<li>皮皮班地址：北京北京北京北京</li>-->
					</ul>
					
				</div>
				<div class="total-wupin">
					<ul>物品信息
						
						<li class="pinpai">巴宝莉皮衣</li>
						
					</ul>
					
				</div>
					<div class="wupin-button">
						<a href="<?php echo U('ppb/ppb/zjjc'); ?>"><button class="yuyue">自己寄出</button></a>
						<a href="<?php echo U('ppb/ppb/index'); ?>"><button> 等待取件</button></a>
					</div>
				</div>
			</div>
	</body>
<script src="/ppb/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	
	
	var name =localStorage.getItem('name');
		var mobile =localStorage.getItem('mobile');
		var province =localStorage.getItem('province');
		var city =localStorage.getItem('city');
		var twon =localStorage.getItem('twon');
		var address =localStorage.getItem('address');
		var wppinpai =localStorage.getItem('wppinpai');
		var wpname =localStorage.getItem('wpname');
		
		
		$(".name").html(name);
		$(".mobile").html(mobile);
		
		var dizhi="";
		var pinpai2="";
		dizhi+=`
			地址：${province}${city}${twon}${address}
		`
		$(".province").html(dizhi);
		pinpai2+=`
			${wppinpai}   ${wpname}
		`
		$(".pinpai").html(pinpai2);
		
		
		
//		$("body").on("click",".yuyue",function(){
//		$.ajax({
//			type:"get",
//			url:"http://root/index.php/ppb/index/edit_data",
//			data : {nickname:name,mobile:mobile,goods_num:1,cat_id:},//数据
//          async : true,//同步
//          dataType : 'json',
//          success : function(data){
//          	
//          	
//          }
//		});
//		
//		})
</script>
</html>