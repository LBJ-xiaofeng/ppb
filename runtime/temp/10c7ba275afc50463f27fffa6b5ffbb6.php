<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:61:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\gj_jdgllist.html";i:1539937646;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<title>进度管理</title>
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no' />
	<style type="text/css">
		body,div,p,ul,li{
			padding: 0;
			margin: 0;
			list-style: none;
		}
		
        .all{
            overflow: hidden;
            padding: 10px 5%;
            border:1px solid #FD9D13;
        }
       
        .all>div>p{
            padding: 2px 0;
            font-size: 14px;
        }
        .all>div{
            color: #484848;
        }
        .all>div>p:first-child>span:last-child{
            color: #FD9D13;
            float: right;
        }
        .one{
          float: right;
          margin-right: 5%;
           color: #FD9D13;
               margin-top: -20px;
               font-size: 15px;
        }
        .ckxq{

          cursor: pointer;
        }
</style>
<link rel="stylesheet" type="text/css" href="/ppb/css/mui.css"/>
</head>
<body>
	<header class="mui-bar mui-bar-nav" style="background-color: #FD9D13;">
  <a class="mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo U('ppb/ppb/gj_index'); ?>" style="color: #fff;"></a>
  <a class="mui-icon mui-icon-reply mui-pull-right"></a>
  <h1 class="mui-title" style="color: #fff;">进度管理</h1>
  </header>
  <div style="margin-top: 60px;"></div>
  <div class="jindulist"></div>
  	<div class="clickAdd more">
		
	</div>
</body>
<script src="/ppb/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
var page=1;
var number;
 $.ajax({
		type:"get",
		url:"http://ppb.dhxdrawing.top/index.php/ppb/admin/myOrderList",
		dataType: 'JSON', //数据格式:JSON
		data: {admin_id:localStorage.getItem('admin_id'),type:1,page:page},
		success: function(data) {
			number=data.data.countPage;
			html="";
			if (data.data.order.length==0) {
				$(".jindulist").html("暂无订单");
				$(".jindulist").css("text-align","center");
				$(".jindulist").css("width","100%");
				$(".jindulist").css("margin-top","40%");
			} else{
				
			
			for (var i=0;i<data.data.order.length;i++) {
				html+=`
						<div class="all" style="margin-top: 20px;">
						    <div>
						        <p><span>寄件人：</span><span>${data.data.order[i].consignee}</span><span>进行中</span></p>
						        <p><span>手机号：</span><span>${data.data.order[i].mobile}</span></p>
						        <p><span>下单时间：</span><span>${data.data.order[i].add_time}</span></p>
						        <p><span>订单号：</span><span>${data.data.order[i].order_sn}</span></p>
						       
						       <span class="one ckxq" data="${data.data.order[i].order_id}">查看详情</span>
						    </div>
						</div>			
				`
				$(".jindulist").html(html);
				$(".clickAdd").html(`<p style="margin-bottom: 20px;text-align: center;margin-top: 10px;" class="con">加载更多</p>`);
			
			}
		}
	}
});
	localStorage.removeItem('ddxqid');
	$('.more').click(function(){
      	page++;
        console.log(page,number);
    if(page>number){
      $('.con').html('已无更多数据');
    }else{
		$.ajax({
		type:"get",
		url:"http://ppb.dhxdrawing.top/index.php/ppb/admin/myOrderList",
		dataType: 'JSON', //数据格式:JSON
		data: {admin_id:localStorage.getItem('admin_id'),type:1,page:page},
		success: function(data) {
			html="";
			if (data.data.order.length==0) {
				$(".jindulist").html("暂无订单");
				$(".jindulist").css("text-align","center");
				$(".jindulist").css("width","100%");
				$(".jindulist").css("margin-top","40%");
			} else{
				
			
			for (var i=0;i<data.data.order.length;i++) {
				html+=`
						<div class="all" style="margin-top: 20px;">
						    <div>
						        <p><span>寄件人：</span><span>${data.data.order[i].consignee}</span><span>进行中</span></p>
						        <p><span>手机号：</span><span>${data.data.order[i].mobile}</span></p>
						        <p><span>下单时间：</span><span>${data.data.order[i].add_time}</span></p>
						        <p><span>订单号：</span><span>${data.data.order[i].order_sn}</span></p>
						       
						       <span class="one ckxq" data="${data.data.order[i].order_id}">查看详情</span>
						    </div>
						</div>			
				`
				$(".jindulist").append(html);
			
			}
		}
	}
});
    }
})
$("body").on("click",".ckxq",function(){
	localStorage.removeItem('ddxqid');
	localStorage.setItem("ddxqid",$(this).attr('data'));
	location.href="<?php echo U('ppb/ppb/gj_jdgl'); ?>";
})
</script>
</html>