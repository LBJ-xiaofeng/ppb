<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:57:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\baoyang.html";i:1540884977;}*/ ?>
<!DOCTYPE html>
<html>
<head >
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=.mian-ul1 li, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <title>皮皮班</title>
        <link href="/ppb/css/mui.min.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="/ppb/css/shop.css" />
        <link rel="stylesheet" type="text/css" href="/ppb/css/aui.css"/>
        <link rel="stylesheet" type="text/css" href="/ppb/css/style.css">
         <style type="text/css">
         body{
            background-color: #fff;
        }
        .mine-ul1{
            height: auto;
            overflow: hidden;
            padding-bottom: 20px;
            margin-top: 60px;
        }
        .mine-ul1 li{
            float: left;
            width:24%;
            text-align: center;
            list-style: none;
             font-size: 16px;
        }
        .mine-ul1 li p{
            margin-top: 5px;
        }
      
        .mine-ul1 li.active{
            color: #ffbfc6;
        }
        .left{
               
    display: inline-block;
    margin-top: 6px;
        }
        .list-inset{
          margin-bottom: 30px;
              height: 135px;
        }
        .zhezhao{
          display: none;
        z-index: 10000;
        background-color: rgba(0, 0, 0, 0.5);
        height: 100%;
        width: 100%;
        position: fixed;
        top: 0;
        }
        .tanchu{
              width: 66%;
          height: 130px;
          background-color: #FFF;
          position: fixed;
          bottom: 50%;
          left: 50%;
          margin-top: -50px;
          margin-left: -30%;
          z-index: 100;
          border-radius: 5px;
        }
        .zffs1{
          width: 45%;
          display: inline-block;
          float: left;
          text-align: center;
          border: 2px solid #ffbfc6;
          font-size: 15px;
          margin-left: 3%;
          margin-top: 17px;
          border-radius:30px ;
        }
        .zffs2{
          width: 45%;
          display: inline-block;
          float: right;
          text-align: center;
          border: 2px solid #ffbfc6;
          font-size: 15px;
          margin-right: 3%;
          margin-top: 17px;
          border-radius:30px ;
        }
    </style>
</head>
<body>
  <header class="mui-bar mui-bar-nav" >
	
		<a class="mui-icon mui-icon-reply mui-pull-right"></a>
		<h1 class="mui-title" >订单</h1>
  </header>
     <ul class="mine-ul1">
       <!--  <li>
          
            全部
        </li> -->
         <a href="<?php echo U('ppb/ppb/daifahuo'); ?>">
         <li >
            定损中
        </li>
        </a>
        <a href="<?php echo U('ppb/ppb/baoyang'); ?>">
             <li class="active">
            保养中
        </li>
        </a>
        <a href="<?php echo U('ppb/ppb/dsh'); ?>">
            <li>
          待送回
        </li>
        </a>
        <a href="<?php echo U('ppb/ppb/dpj'); ?>">
            <li >
            待评价
        </li>
        </a>
    </ul>


    <div class="zhezhao">
<div class="tanchu">
  <div style="width: 100%;font-size: 16px;text-align: center;margin-top: 8%;">选择支付方式</div>
  <div class="zffs1" type="0">微信支付</div>
  <div class="zffs2" type="1">余额支付</div>
  <div class="quxiaozf" style="width: 100%;font-size: 15px;text-align: center;margin-top: 22%;">取消</div>
</div>
</div>
    <div id="content2">
      
     
      </div>
</div>
<p style="margin-bottom: 100px"></p>
        <footer class='aui-bar aui-bar-tab aui-bar-tab-cl aui-border-t gddw' id='footer'>
            
            <a href="<?php echo U('ppb/ppb/index'); ?>" class='aui-bar-tab-item ' tapmode>
                <i><img src="/ppb/image/mine(2).png"></i>
                
            </a>
            
            <a class='aui-bar-tab-item' tapmode href="<?php echo U('ppb/ppb/daifahuo'); ?>">
                
                <i><img src="/ppb/image/mine(3).jpg"></i>
            </a>
                <a class='aui-bar-tab-item' tapmode href="<?php echo U('ppb/ppb/member'); ?>">
                
                <i><img src="/ppb/image/mine (5).jpg"></i>
            </a>
            
        
        </footer>
    <script src="/ppb/js/jquery.min.js"></script>

<script type="text/javascript">
     $.ajax({
            type : 'POST',
            url : 'http://ppb.dhxdrawing.top/index.php/ppb/Order/orderList',
            data : {user_id:localStorage.getItem('user_id'),type:1},//数据
            async : true,//同步
            dataType : 'json',
            success : function(data){
                console.log(data);  
                var html = '';
                if(data.data.length==0){
                      $('#content2').html('暂无订单！');
                       $('#content2').css('textAlign','center');
                }else{
                   
                  for(var i=0;i<data.data.length;i++){
        //           	if (data.data[i].goods_price>0 && data.data[i].pay_status==0) {
        //           		                    html+=`
        //      <ul class="list list-inset">
        //         <p class="dfk_p1"><span style="float: left;">${data.data[i].add_time}</span><span style="float: right;">保养中</span></p>
             

        // <li class="item item-icon-left item-icon-right">
       
        //     <p><span class="names">${data.data[i].mobile_name}</p>
        //      <p><span class="">${data.data[i].brand_name}/${data.data[i].attr_name}/${data.data[i].mobile_name}</span></p>
        //       <p style="margin-top: -41px;float:right;">
	           
	       //        <span class="prices">¥${data.data[i].goods_price} </span>
	       //      	<span class="num">x${data.data[i].goods_num} &nbsp; </span> 
        //     </p>

        // </li>
        // <button class="btn chakan" order="${data.data[i].order_id}">查看详情</button>
        //  <button class="btn zhifu" order="${data.data[i].order_id}">立即支付</button>
          
        //  </ul>
        //             `
 
     
        //         $('#content2').html(html); 
        //           	} else{
                  		                    html+=`
             <ul class="list list-inset">
                <p class="dfk_p1"><span style="float: left;">${data.data[i].add_time}</span><span style="float: right;">保养中</span></p>
             

        <li class="item item-icon-left item-icon-right">
       
            <p><span class="names">${data.data[i].mobile_name}</p>
             <p><span class="">${data.data[i].brand_name}/${data.data[i].attr_name}/${data.data[i].mobile_name}</span></p>
              <p style="margin-top: -41px;float:right;">
	           
	              <span class="prices">¥${data.data[i].goods_price} </span>
	            	<span class="num">x${data.data[i].goods_num} &nbsp; </span> 
            </p>

        </li>
        <button class="btn chakan" order="${data.data[i].order_id}">查看详情</button>
        
          
         </ul>
                    `
 
     
                $('#content2').html(html); 
                  	// }

                  }
            }
            }

        })
     $('body').on('click' , '.pingjia' , function(){
            localStorage.setItem('goods_id',$(this).attr('data'))
              localStorage.setItem('order_id',$(this).attr('id'))
                 window.location.href="<?php echo U('ppb/ppb/pingjia'); ?>";
         })
    $('body').on('click','.tiao',function(){
        localStorage.setItem('order_id',$(this).attr('data'));
        window.location.href="<?php echo U('ppb/ppb/order_details'); ?>";
    })
    $('body').on('click','.chakan',function(){
        localStorage.setItem('order',$(this).attr('order'));
        window.location.href="<?php echo U('ppb/ppb/ddxq'); ?>";
    })
    $(".quxiaozf").click(function(){
      
      $(".zhezhao").toggle();
    })
    localStorage.removeItem('order');
     $('body').on('click','.zhifu',function(){
      $(".zhezhao").css("display","block");
      localStorage.removeItem('order');
       localStorage.setItem('order',$(this).attr('order'));
       
    })
     $('body').on('click','.zffs1',function(){
      window.location.href="http://ppb.dhxdrawing.top/index.php/ppb/Order/orderPay/type/0/order_id/"+localStorage.getItem('order');
    //     $.ajax({
    //       type:"get",
    //       url:"http://ppb.dhxdrawing.top/index.php/ppb/Order/orderPay",
    //       data : {order_id:localStorage.getItem('order'),type:0},//数据
    //         dataType : 'json',
    //         success : function(data){
    //           alert(data.msg);
    //           location.href=location.href;
    //         }
    //     });
    })
    $('body').on('click','.zffs2',function(){
      
        $.ajax({
          type:"get",
          url:"http://ppb.dhxdrawing.top/index.php/ppb/Order/orderPay",
          data : {order_id:localStorage.getItem('order'),type:1},//数据
            dataType : 'json',
            success : function(data){
              alert(data.msg);
              location.href=location.href;
            }
        });
    })
</script>
</body>
</html>