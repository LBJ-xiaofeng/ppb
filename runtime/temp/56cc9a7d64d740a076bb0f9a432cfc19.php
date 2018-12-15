<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:57:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\gj_ddgl.html";i:1541412395;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<title>订单管理</title>
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
        a{
            color: #242424;
        }
         .mine-ul1{
            height: auto;
            overflow: hidden;
            padding-bottom: 20px;
            margin-top: 60px;
        }
        .mine-ul1 li{
            float: left;
            width:20%;
            text-align: center;
            list-style: none;
             font-size: 16px;
        }
        .mine-ul1 li p{
            margin-top: 5px;
        }
      
         .mine-ul1 li.active{
            color: #FD9D13;
        }
</style>
<link rel="stylesheet" type="text/css" href="/ppb/css/mui.css"/>
</head>
<body>
	<header class="mui-bar mui-bar-nav" style="background-color: #FD9D13;">
  <a class="mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo U('ppb/ppb/gj_index'); ?>" style="color: #fff;"></a>
  <a class="mui-icon mui-icon-reply mui-pull-right"></a>
  <h1 class="mui-title" style="color: #fff;">订单管理</h1>
  </header>
     <ul class="mine-ul1">
        <a href="<?php echo U('ppb/ppb/gj_allorder'); ?>" style="color: black;">
             <li>
            全部
        </li>
        </a>
         <a href="<?php echo U('ppb/ppb/gj_ddgl'); ?>">
         <li class="active">
            待定损
        </li>
        </a>
        <a href="<?php echo U('ppb/ppb/gj_baoyang'); ?>"style="color: black;">
             <li>
            保养中
        </li>
        </a>
        <a href="<?php echo U('ppb/ppb/gj_dsh'); ?>"style="	color: black;">
            <li>
          待送回
        </li>
        </a>
        <a href="<?php echo U('ppb/ppb/gj_yiwancheng'); ?>"style="	color: black;">
            <li>
            待评价
        </li>
        </a>
    </ul>
  <div class="start">
   </div> 
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
		data: {admin_id:localStorage.getItem('admin_id'),type:0,page:page},
		success: function(data) {
      number=data.data.countPage;
			var html="";
			if (data.data.order.length==0) {
				$(".start").html("暂无订单");
				$(".start").css("text-align","center");
				$(".start").css("width","100%");
				$(".start").css("margin-top","40%");
			} else{
			for (var i=0;i<data.data.order.length;i++) {
	
			html+=`
				<div class="all">
				    <div>
				        <p><span>用户名：</span><span>${data.data.order[i].consignee}</span><span>${data.data.order[i].order_status}</span></p>
				        <p><span>手机号：</span><span>${data.data.order[i].mobile}</span></p>
				        <p><span>下单时间：</span><span>${data.data.order[i].add_time}</span></p>
				        <p><span>订单号：</span><span>${data.data.order[i].order_sn}</span></p>
				      
				        <span class="one ckxq" style="cursor: pointer;" gjddid="${data.data.order[i].order_id}">查看详情</span>
				    </div>
				</div>
			`	
			}
			$(".start").html(html);
      $(".clickAdd").html(`<p style="margin-bottom: 20px;text-align: center;margin-top: 10px;" class="con">加载更多</p>`);
			}
		}
	});
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
    data: {admin_id:localStorage.getItem('admin_id'),type:0,page:page},
    success: function(data) {
      var html="";
      if (data.data.order.length==0) {
        $(".start").html("暂无订单");
        $(".start").css("text-align","center");
        $(".start").css("width","100%");
        $(".start").css("margin-top","40%");
      } else{
      for (var i=0;i<data.data.order.length;i++) {
          
        
      html+=`
        <div class="all">
            <div>
                <p><span>用户名：</span><span>${data.data.order[i].consignee}</span><span>保养中</span></p>
                <p><span>手机号：</span><span>${data.data.order[i].mobile}</span></p>
                <p><span>下单时间：</span><span>${data.data.order[i].add_time}</span></p>
                <p><span>订单号：</span><span>${data.data.order[i].order_sn}</span></p>
                <!--<span class="one fenpei" gjddid="${data.data.order[i].order_id}" style="margin-right: 0">分配人员</span>-->
                <span class="one ckxq" gjddid="${data.data.order[i].order_id}">查看详情</span>
            </div>
        </div>
      ` 
      }
      $(".start").append(html);
      }
    }
  });
    }
})
	$("body").on("click",".ckxq",function(){
		localStorage.setItem("gjddid",$(this).attr('gjddid'))
		location.href="<?php echo U('ppb/ppb/gj_ckdd2'); ?>";
	})
</script>
</html>