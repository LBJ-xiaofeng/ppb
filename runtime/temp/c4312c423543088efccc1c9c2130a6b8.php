<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:58:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\gj_ckdd2.html";i:1541051588;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<title>查看订单</title>
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no' />
	<link rel="stylesheet" type="text/css" href="/ppb/css/fabu.css">
	<link rel="stylesheet" href="/ppb/pictureViewer.css">
	<style type="text/css">
		body,div,p,ul,li{
			padding: 0;
			margin: 0;
			list-style: none;
		}
		.all{
			padding: 10px 5%;
		}
		.all>p{
			padding: 10px 0;
			font-size: 16px;
		}
		.all>div>p{
			padding: 4px 0;
		}
		.all>div{
			color: #484848;
		}
		 footer{
        	position: absolute;
        	bottom: 0;
        	left: 0;
        	width: 100%;
        	text-align: right;
        }
        .one{ 
        		 margin: 0;
			    padding: 0;
			    background-color: #73E96E;
			    width: 27%;
			    color: #fff;
			    padding: 9px 0;
			    border: none;
			    font-size: 14px;
			    margin-right: -5px;
        }
        .two{
        		margin: 0;
        		padding: 0;
        	    background-color: #FD9D13;
			    width: 27%;
			    color: #fff;
			    padding: 9px 0;
			    border: none;
			    font-size: 14px;
        }
         #btn {
            width: 60px;
    		height: 45px;
            text-align: center;
          
            margin: 10px auto;
        }
        
        .album {
            width: 100%;
           
            height: auto;
            min-height: 50px;
            display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
            

        }
        .albumboss{
        	display: none;
        }
        .album>div {
            width: 23%;

            height: auto;
            margin-right: 5px;
            margin-top:7px;
        }
        
        .album>div>img {
            width: 50px;
            height: 70px
            border-radius: 5px;
        }
        .kyzezp{
        	display: 
        }
       .yishangchuan,.yixuanze{
       	width: 100%;
       	 display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
       }
        .yishangchuan div,.yixuanze div{
        	 width: 23%;

        	 margin-right: 5px;
            margin-top:7px;
        }
       .yishangchuan div img,.yixuanze div img{
       	 width: 50px;
            height: 70px;
            border-radius:5px;
       }
       .zzcew{
		    display: none;
		    width: 100%;
		    height: 700px;
		    background: rgba(0,0,0,0.3);
		    position: fixed;
		    top: 0;
		    z-index: 999;
       }
       .imgew{
       	    width: 200px;
		    height: 200px;
		    display: none;
		    position: fixed;
		    top: 30%;
		    margin-left: 21%;
		    z-index: 9999;
       }
       .setewm{
	       	margin: 0;
	       	padding: 0;
	       	background-color: #F10D0E;
	       	width: 100%;
	       	color: #fff;
	       	padding: 10px 0;
	       	border: none;
	       	font-size: 16px;
	       	position: fixed;
	       	bottom: 0px;
       }
       .blockto{
       	width: 300px;
       	height: 300px;
       	display: none;
       }
       .qrds{
            width: 100px;
            height: 30px;
            line-height: 30px;
            border: 1px solid #f15105;
            color: #fff;
            background: #f15105;
            font-weight: bold;
            text-align: center;
            border-radius: 15px;
            margin: 10px auto;
            font-size: 16px;
        }
        .xuanzhong{
        border:2px solid #f15105 !important;
    }
	</style>

	<link rel="stylesheet" type="text/css" href="/ppb/css/gong.css">
<script type="text/javascript" src="/ppb/js/rem.H5.js"></script>

</head>
<body>
	<div class="start">

</div>
	<div class="all">
		<p>添加价格</p>
		<div>
		<input type="text" placeholder="加价理由" class="tianjialy" style="width: 35%;height: 30px;border-radius: 10px;"/>
		<input type="text" placeholder="添加金额" class="tianjiajg" style="width: 35%;height: 30px;border-radius: 10px;"/>
		<input type="button" value="确定" class="quedtjj" style="height: 35px;margin-left: 5%;width: 20%;border-radius: 10px;color: #333;"/>
		</div>
</div>
	<div class="all">
		<p>价格信息</p>
		<div class="price"></div>
</div>
<div class="all">
		<p>已上传照片</p>
		 <div class="kyzezp yishangchuan">
		 </div>
		 <span class="qrchanga"> </span>
		 
</div>

<div class="all">
		<p>客户已选照片</p>
		 <div class="kyzezp yixuanze">
		 	
		 </div>
		

</div>
<form style="margin-bottom: 50px;" action="http://ppb.dhxdrawing.top/index.php/ppb/admin/submitimg" method="post" enctype="multipart/form-data" id="formLogin"  onsubmit = "return false"/>

<div class="all">
		<p>上传定损照片</p>
		 <div class="albumboss"><div class="album"></div></div>
		<div class="fb_shangchuan" style="margin-top: 10px; display: block;">
		 <!-- <input type="submit" /> -->
    <div class="album" style="margin-bottom: 15px;"></div>
			<img src="/ppb/img/1_03.png"  class="clickpz" />
			<div id="change" style="width: 100%;height: 300px;position: fixed;bottom: 0;left: 0;z-index: 99;color: #fff;text-align: center;line-height: 50px;font-size: 18px;display: none;">
				<div class="closepage" style="height: 200px;border-bottom: 1px solid #fff;background:color;"></div>
				<div id="phone" style="height: 50px;border-bottom: 1px solid #fff;background: #f15105;">拍照上传</div>
				<div id="picture" style="background: #f15105;">图库选择</div>
			</div>
            <input id="changeimg" type="file" name="img[]" onchange="previewImage(this,5)"  style="display: none">
			<input id="takepicture" type="file" name="img[]"  accept="image/*" capture="camera" style="display: none">
			<div style="margin-top: 30px; display: block;border: 1px solid;border-radius: 5px;width: 162px;margin: 0 auto; background-color: #fff; height: 26px;display: none;line-height: 26px;" class="quedingsc"  data="1">确定上传</div>
			
		</div>

</div>
<input type="text" hidden="hidden" value="" name="order_id" class="shangcimg"></input>
</form>

	    <span class="clickewm"></span>
		<div class="zzcew"></div>
		<div class="blockto"><img class="imgew" src=""></div>


</body>
<script src="/ppb/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>

<script src="/ppb/js/mui.js" type="text/javascript" charset="utf-8"></script>
<!-- <script src="/ppb/js/cslCamera.js" type="text/javascript" charset="utf-8"></script> -->
<script type="text/javascript" src="/ppb/js/formDate.js"></script>
<script src="/ppb/jquery.mousewheel.min.js"></script>
 <script src="/ppb/pictureViewer.js"></script>
<script src="/ppb/laydate.js"></script>
<script type="text/javascript">

//拍照上传
 $('.clickpz').click(function(){
    $("#change").show();
  })
 $('.closepage').click(function(){
    $("#change").hide();
  })
 $('#phone').click(function(){
 	$('#takepicture').removeAttr('disabled');
 	$('#takepicture').click();
 	$("#change").hide();
 	$('#changeimg').attr('disabled','disabled');
 })
 $('#picture').click(function(){
 	$('#changeimg').removeAttr('disabled');
 	$('#changeimg').click();
 	$("#change").hide();
 	$('#takepicture').attr('disabled','disabled');
 })
//图库选择
  function previewImage(file,imgNum){
    var reader = new FileReader();
    reader.onload = function(event){
    var imgnum=0
    var div = $("<div></div>");
    var img = $('<img/>');
    div.append(img);
    $(".album").append(div);
    $(".quedingsc").attr("data","2");
    img.attr('src',event.target.result);
    $(".quedingsc").css("display","block");
    $(".clickpz").css("display","none");
    }
    reader.readAsDataURL(file.files[0]);

    }

// 相机拍摄
var takePicture = document.getElementById('takepicture');
  var takePictureUrl = function () {
      takePicture.onchange = function (event) {
          var files = event.target.files, file;
         if (files && files.length > 0) {
              file = files[0];
              try {
                var URL = window.URL || window.webkitURL;
                 var blob = URL.createObjectURL(file);　　// 获取照片的文件流
                 compressPicture(blob);　　// 压缩照片
             }
             catch (e) {
                 try {
                     var fileReader = new FileReader();
                     fileReader.onload = function (event) {　　　　// 获取照片的base64编码

                         // console.log(event.target.result);
                        var imgnum=0
                          var div = $("<div></div>");
            			var img = $('<img/>');
            			  div.append(img);
            			   $(".album").append(div);
            			   $(".quedingsc").attr("data","2");
            			   	 img.attr('src',event.target.result);
            			   	$(".quedingsc").css("display","block");
            			   		$(".clickpz").css("display","none");
          
                     };
                     fileReader.readAsDataURL(file);
                 }
                 catch (e) {
                   alert(common.MESSAGE.title.error, '拍照失败,请联系客服或尝试更换手机再试!');
                 }
             }
        }
   }
 }();

$("body").on("click",".quedtjj",function(){
		var msg=$(".tianjialy").val();
		var price=$(".tianjiajg").val();
			$.ajax({
				type:"get",
				url:"http://ppb.dhxdrawing.top/index.php/ppb/admin/submitimgorprice",
				dataType: 'JSON', //数据格式:JSON
				data: {
					admin_id:localStorage.getItem('admin_id'),
					msg:msg,
					price:price,
					order_id:localStorage.getItem('gjddid')
				},
				success: function(data) {
					if (data.status==1) {
						alert("操作成功");
						location.reload();
					}else{
						alert("网络错误");
					}
				}
			})


})
 $("#formLogin").ajaxForm(function(data){
          	 	 var jsonObj =  JSON.parse(data)
               if(jsonObj.status==1){

             alert("上传成功");
             		$(".quedingsc").css("display","none");
            		$(".clickpz").css("display","block");
            		location.reload();
             } else{
                alert(jsonObj.msg);
             }
          })
$("body").on("click",".quedingsc",function(){

	if ($(this).attr('data')==1) {
			alert("请先拍摄图片")
          }else{
          	
          	$(".shangcimg").val(localStorage.getItem('gjddid'));
          	$("#formLogin").submit();
          	
        }
        return false;
    });




	$.ajax({
		type:"get",
		url:"http://ppb.dhxdrawing.top/index.php/ppb/admin/editOrder",
		dataType: 'JSON', //数据格式:JSON
		data: {order_id:localStorage.getItem('gjddid')},
		success: function(data) {
			var html="";
			var price="";
			var yisc="";
			var yxz="";
			if(data.data.order.checkedimg[0]!=null){
				for (var i = 0; i<data.data.order.checkedimg.length;i++) {

				yxz+=`
				<div><img src="${data.data.order.checkedimg[i]}" ></div>

				`
				}
				$(".yixuanze").html(yxz);
			}else{
				$(".yixuanze").html(`<span style="margin-left:10px;">暂未选择</span>`);
			}
			
			if(data.data.order.order_prom_type.length>0){
			for (var i = 0; i<data.data.order.order_prom_type.length;i++) {

			yisc+=`
			<div><img src="${data.data.order.order_prom_type[i]}" class="zuanzeimg" id="${i}" ></div>
			`
			}
			$(".yishangchuan").html(yisc);
			$(".qrchanga").html(`<div class="qrds">确认选择</div>`);
			}else{
				$(".yishangchuan").html(`<span style="margin-left:10px;">暂未选择</span>`);
			}
			html+=`
				<div class="all">
						<p>订单信息</p>
						<div>
							<p><span>订单号：</span><span>${data.data.order.order_sn}</span></p>
							<p><span>物品名称：</span><span>${data.data.order.mobile_name}-${data.data.order.attr_name}-${data.data.order.brand_name}</span></p>
							<p><span>下单时间：</span><span>${data.data.order.add_time}</span></p>

						</div>
					</div>
					<div class="all">
						<p>基本信息</p>
						<div>
							<p><span>寄件人：</span><span>${data.data.order.consignee}</span></p>
							<p><span>寄件人电话：</span><span>${data.data.order.mobile}</span></p>
							<p><span>寄件人地址：</span><span>${data.data.order.province} ${data.data.order.city}  ${data.data.order.twon}${data.data.order.address}</span></p>

						</div>
					</div>
					<div class="all">
						<p>订单进度</p>
						<div style="color: #FD9D13">
							${data.data.order.order_status}  · · ·${data.data.order.checkstatus}
						</div>
						<span style="color: oranged">
							${data.data.order.checkmsg}&nbsp;&nbsp;&nbsp;&nbsp;${data.data.order.checktime}
						</span>
					</div>
					<div class="all">
						<p>物流信息</p>
						<div>
							<p>寄件时间:  ${data.data.order.send_shipping_time}</p>
							<p>快递公司:  ${data.data.order.send_shipping_code}</p>
							<p>快递单号:  ${data.data.order.send_shipping_name}</p>
							
						</div>
					</div>
					<div class="all">
						<p>支付时间</p>
						<div>
							<input type="text" placeholder="请选择日期" readonly="readonly" class="zhifusj" style="height: 30px;width: 70%;border-radius: 10px;" id="yy_time" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})"></input>
							<input type="button" value="确定" class="quedtj" style="height: 35px;margin-left: 5%;width: 20%;border-radius: 10px;color: #333;"></input>

						</div>
					</div>
					<div class="all">
						<p>物品信息</p>
						<div>
						<p>${data.data.order.mobile_name}   	 ${data.data.order.attr_name}		${data.data.order.brand_name}</p>
							
						</div>
					</div>
					
			
			`
			if(data.data.order.pay_status==1){
				$('.clickewm').html('<button class="scewm setewm">生成二维码</button>');
			}
			if (data.data.money.length==0) {
				$(".price").html('暂无信息');
				$(".price").css("text-align","left");
				$(".price").css("width","100%");
			} else{
				
			
			for (var i=0;i<data.data.money.length;i++) {
				price+=`
				<div>
						<p>&emsp;${data.data.money[i].title} &emsp;&emsp;&emsp;&emsp;￥${data.data.money[i].money}</p>
							
						</div>
								
				`
				$(".price").html(price);
			
			}
		}
			$(".start").html(html);
		}
			
	});

	$("body").on("click",".quedtj",function(){
		var pay_time=$(".zhifusj").val();
		
		if (pay_time!=="") {
			$.ajax({
				type:"get",
				url:"http://ppb.dhxdrawing.top/index.php/ppb/admin/setpaytime",
				dataType: 'JSON', //数据格式:JSON
				data: {
					pay_time:pay_time,
					order_id:localStorage.getItem('gjddid')
				},
				success: function(data) {
					if (data.status==1) {
						alert("操作成功");
						location.reload();
					}else{
						alert("网络错误");
					}
				}
			})
		}else{
			alert("请填写完整");
		}


})

			$('.yishangchuan,.yixuanze').on('click', 'div', function () {
        var this_ = $(this);
        var images = this_.parents('.yishangchuan,.yixuanze').find('div');
        var imagesArr = new Array();
        $.each(images, function (i, image) {
            imagesArr.push($(image).children('img').attr('src'));
        });
        $.pictureViewer({
            images: imagesArr, //需要查看的图片，数据类型为数组
            initImageIndex: this_.index() + 1, //初始查看第几张图片，默认1
            scrollSwitch: true //是否使用鼠标滚轮切换图片，默认false
        });
    });




   		$("body").on("click",".scewm",function(){
		
		$.ajax({
		type:"get",
		url:"http://ppb.dhxdrawing.top/index.php/ppb/admin/makecode",
		dataType: 'JSON', //数据格式:JSON
		data: {oid:localStorage.getItem('gjddid')},
		success: function(data) {
			console.log(data)
			if (data.status==0) {
				alert(data.msg);
			} else{
				$(".imgew").attr('src',data.data);
				$(".imgew").css("display","block");
				$(".zzcew").css("display","block");
				$(".blockto").css("display","block");
			}
		}
	});
	})


   		$("body").on("click",".zzcew",function(){
		
				$(".imgew").css("display","none");
				$(".zzcew").css("display","none");
				$(".blockto").css("display","none");
	})

   	$("body").on("click",".zuanzeimg",function(){

    if ( $(this).attr("data")==1) {
          $(this).removeClass("xuanzhong");
          $(this).attr("data","2");
        } else{
            $(this).addClass("xuanzhong");
            $(this).attr("data","1");
        }
	})

	$("body").on("click",".qrchanga",function(){
    var xuanzeimgboos = new Array();
$('.yishangchuan div img').each(function(i){

    if($(this).attr('data')==1){
         thisid=$(this).attr('id');
     xuanzeimgboos.push(thisid);

    }
})
    if(xuanzeimgboos==""){
        alert("请选择照片");
    }
    else{

 $.ajax({
            type:"get",
            url:"http://ppb.dhxdrawing.top/index.php/ppb/order/changeimage",
            data:{order_id: localStorage.getItem('gjddid'),number:xuanzeimgboos.join(",")},//数据
            async : true,//同步
            dataType : 'json',
            success : function(data){
                if (data.status==1) {
                    alert("选择成功");
                  location.reload();
                } else {
                    alert(data.msg);
                }
            }
           })
}


})

</script>

<script type="text/javascript">

!function(){

  laydate.skin('molv');//切换皮肤，请查看skins下面皮肤库

  laydate({elem: '#demo'});//绑定元素

}();



//日期范围限制

var start = {

    elem: '#start',

    format: 'YYYY-MM-DD',

    min: laydate.now(), //设定最小日期为当前日期

    max: '2099-06-16', //最大日期

    istime: true,

    istoday: false,

    choose: function(datas){

         end.min = datas; //开始日选好后，重置结束日的最小日期

         end.start = datas //将结束日的初始值设定为开始日

    }

};



var end = {

    elem: '#end',

    format: 'YYYY-MM-DD',

    min: laydate.now(),

    max: '2099-06-16',

    istime: true,

    istoday: false,

    choose: function(datas){

        start.max = datas; //结束日选好后，充值开始日的最大日期

    }

};

laydate(start);

laydate(end);



//自定义日期格式

laydate({

    elem: '#test1',

    format: 'YYYY年MM月DD日',

    festival: true, //显示节日

    choose: function(datas){ //选择日期完毕的回调

        alert('得到：'+datas);

    }

});



//日期范围限定在昨天到明天

laydate({

    elem: '#hello3',

    min: laydate.now(-1), //-1代表昨天，-2代表前天，以此类推

    max: laydate.now(+1) //+1代表明天，+2代表后天，以此类推

});

</script>
</html>