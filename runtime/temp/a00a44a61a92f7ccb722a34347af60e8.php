<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:58:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\medongxi.html";i:1539061576;}*/ ?>
<!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>预约下单</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="/ppb/css/mui.min.css" rel="stylesheet" />
		
		<link rel="stylesheet" type="text/css" href="/ppb/css/medongxi.css"/>
		<script src="/ppb/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<style type="text/css">
		body{
            background-color: #fff;
        }
        .one{
        	cursor: pointer;
        }
        </style>
	</head>

	<body>
		<header class="mui-bar mui-bar-nav" >
			<a class="mui-icon mui-icon-left-nav mui-pull-left" href='javascript:history.go(-1)' style="color: black;"></a>
			<a class="mui-icon mui-icon-reply mui-pull-right"></a>
			<h1 class="mui-title" >预约下单</h1>
	  </header>
  <div class="start">
		<ul class="mui-table-view wplist">

</ul>
	<div class="zwwp"></div>
<a href="<?php echo U('ppb/ppb/yyxd'); ?>">
	<div class="tianjia">
		<div class="tianj">添加</div>
	</div>
</a>

<div class="one">下一步</div>

</div>
	</body>
<script type="text/javascript">
			localStorage.removeItem('wpname');
			localStorage.removeItem('wpid');
			    localStorage.removeItem('wppinpai');	
		$.ajax({
			type:"get",
			url:"http://ppb.dhxdrawing.top/index.php/ppb/index/myDataList",
			async:true,
			dataType: 'JSON', //数据格式:JSON
		data: {user_id:localStorage.getItem('user_id')},
		success: function(data) {
			var html="";
			if (data.data.length==0) {
				html+=`
				<div style="width: 100%;text-align: center; border:0px;color: gray;margin-top: 40%;"><span>暂无物品，快去添加吧</span></div>
				
				`
				$(".wplist").css("display","none");
				$(".zwwp").html(html);
			} else{
				for (var i=0;i<data.data.length;i++) {
					html+=`
						<li class="mui-table-view-cell mui-media xuan" wpid="${data.data[i].id}"  wppinpai="${data.data[i].brand_name}" wpname="${data.data[i].mobile_name}">
							<a href="#">
								<img wpid="${data.data[i].id}" class="shanchuwp mui-media-object mui-pull-right " src="/ppb/img/shanchubut.png"style="width: 20px;height: 28px; margin-top: 10px;">
								<div class="mui-media-body">
									${data.data[i].mobile_name}
									<p class='mui-ellipsis ppwp' >${data.data[i].brand_name} ${data.data[i].mobile_name} ${data.data[i].attr_name} </p>
								</div>
							</a>
						</li>
					`
					$(".wplist").html(html);
				}
			}
			 
		
		}
		});	
		
		
		
		$("body").on("click",".xuan",function(){
				    $(this).addClass("xuanzhong");
				    $(this).siblings().removeClass("xuanzhong");
				    localStorage.removeItem('wpname');
				    localStorage.removeItem('wpid');
				    localStorage.removeItem('wppinpai');
	   				localStorage.setItem("wpname",$(this).attr("wpname"));
	   				localStorage.setItem("wppinpai",$(this).attr("wppinpai"));
	   				localStorage.setItem("wpid",$(this).attr("wpid"));
				  
			})
		$("body").on("click",".shanchuwp",function(){
			if(confirm("确认删除吗")){
		
					var wpid= $(this).attr("wpid");
					
		 $.ajax({
						  	type:"get",
						  	url:"http://ppb.dhxdrawing.top/index.php/ppb/index/delMyData",
						  	async:true,
						  	dataType: 'JSON', //数据格式:JSON
							data: {id:wpid},
							success: function(data) {
								alert(data.msg);
								location.reload();
							}
						  }); 
		}
		else{
		return;
		}				 
			})
		
		$("body").on("click",".one",function(){
			if (localStorage.getItem("wpname")==undefined&&localStorage.getItem("wppinpai")==undefined) {
				alert("请选择您的物品");
			} else{
				location.href="<?php echo U('ppb/ppb/yyqj'); ?>";
			}
		})
</script>
</html>