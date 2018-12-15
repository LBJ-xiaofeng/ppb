<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:59:"E:\phpstudy\WWW\ppb/application/ppb\view\Admin\zjorder.html";i:1541060559;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<title>查看订单</title>
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no' />
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
			font-size: 14px;
			color: #484848;
			padding: 0 5%;
		}
		 footer{
        	position: fixed;
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
        /*.start{
        	margin-bottom: 50px;
        }*/
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
       .yishangchuan,.yixuanze,.zhijian{
       	width: 100%;
       	 display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
       }
        .yishangchuan div,.yixuanze div,.zhijian div{
        	 width: 23%;

        	 margin-right: 5px;
            margin-top:7px;
        }
       .yishangchuan div img,.yixuanze div img,.zhijian div img{
       	 width: 50px;
            height: 70px;
            border-radius:5px;
       }
       .note{
       	width: 90%;
	    height: 30px;
	    display: block;
	    padding-top: 10px;
	    color: red;
	    font-size: 12px;
	    padding-left: 5%;
       }
	</style>
</head>
<body>
	<div class="start">
		<div class="all">
						<p>订单信息</p>
						<div>
							<p><span>订单号：</span><span><?php echo $order['order_sn']; ?></span></p>
							<p><span>下单时间：</span><span><?php echo $order['add_time']; ?></span></p>
							<p><span>完成状态：</span><span style="color: #FD9D13"><?php echo $order['wc_status']; ?></span></p>
							<p><span>质检状态：</span><span style="color: #FD9D13"><?php echo $order['hg_status']; ?></span></p>
						</div>
					</div>
					<div class="all">
						<p>基本信息</p>
						<div>
							<p><span>寄件人：</span><span><?php echo $order['consignee']; ?></span></p>
							<p><span>寄件人电话：</span><span><?php echo $order['mobile']; ?></span></p>
							<p><span>寄件人地址：</span><span><?php echo $order['province']; ?><?php echo $order['city']; ?><?php echo $order['address']; ?></span></p>

						</div>
					</div>
					<div class="all">
						<p>订单进度</p>
						<div style="color: #FD9D13">
							<?php echo $order['order_status']; ?>  · · ·
						</div>
					</div>
					
					<div class="all">
						<p>物品信息</p>
						<div>
						<p><?php echo $order['mobile_name']; ?>-<?php echo $order['attr_name']; ?>-<?php echo $order['brand_name']; ?></p>
							
						</div>
					</div>
					<div class="all test">
						<p>定损照片</p>
						 <div class="kyzezp yishangchuan">
						<?php if(is_array($order['order_prom_type']) || $order['order_prom_type'] instanceof \think\Collection || $order['order_prom_type'] instanceof \think\Paginator): $k = 0; $__LIST__ = $order['order_prom_type'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($k % 2 );++$k;?>
						 <div class="sc1"><img src="<?php echo $order['order_prom_type'][$k-1]; ?>" ></div>
						<?php endforeach; endif; else: echo "" ;endif; ?>
						 </div>
					</div>

					<div class="all">
						<p>客户已选照片</p>
						 <div class="kyzezp yixuanze">
						 <?php if(is_array($order['checkedimg']) || $order['checkedimg'] instanceof \think\Collection || $order['checkedimg'] instanceof \think\Paginator): $k = 0; $__LIST__ = $order['checkedimg'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($k % 2 );++$k;?>
						 <div class="sc2"><img src="<?php echo $order['checkedimg'][$k-1]; ?>" ></div>
						<?php endforeach; endif; else: echo "" ;endif; ?>
						 	
						 </div>
					</div>
					<div class="all">
						<p>已上传质检照片</p>
						 <div class="kyzezp zhijian">
						 <?php if(is_array($order['integral']) || $order['integral'] instanceof \think\Collection || $order['integral'] instanceof \think\Paginator): $k = 0; $__LIST__ = $order['integral'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($k % 2 );++$k;?>
						 <div class="sc3"><img src="<?php echo $order['integral'][$k-1]; ?>" ></div>
						<?php endforeach; endif; else: echo "" ;endif; ?>
						 </div>
					</div>
					<div class="bh">
						<div class="all">
						<p>查验信息</p>
					   <?php if(is_array($action) || $action instanceof \think\Collection || $action instanceof \think\Paginator): $k = 0; $__LIST__ = $action;if( count($__LIST__)==0 ) : echo "没有数据" ;else: foreach($__LIST__ as $key=>$val): $mod = ($k % 2 );++$k;?>
						<div style="font-size: 14px;padding: 0 5%;margin-top: 15px;border: 1px solid #eee;">
							<p><span><?php echo $val['admin_name']; ?></span><span  style="font-size: 15px; color:black;margin-left: 30%;"><?php echo $val['add_time']; ?></span></p>
							<p><span style="vertical-align: top">意见：</span><span ><?php echo $val['action_note']; ?></span></p>
						</div>
					<?php endforeach; endif; else: echo "没有数据" ;endif; ?>
				</div>
					</div>
	</div>
	<div class="all">
		<p>添加快递单号</p>
		<?php if($order['shipping_code']): ?>
			<p><span>快递单号：</span><span><?php echo $order['shipping_code']; ?></span></p>
		<?php endif; ?>

		<div>
		<input type="text" placeholder="快递单号" class="kdhm" style="height: 30px;width: 70%;border-radius: 10px;"/>
		<input type="button" value="确定" class="quedtjj" style="height: 35px;margin-left: 5%;width: 20%;border-radius: 10px;color: #333;"/>
		</div>
		<span class="note">*填写单号时间即为货物出库时间</span>
	</div>
	<form action="http://ppb.dhxdrawing.top/index.php/ppb/admin/checkimage" method="post" enctype="multipart/form-data" id="formLogin"  onsubmit = "return false"/>

<div class="all">
<p><?php echo $order['zjname']; ?></p>
		 <div class="albumboss"><div class="album"></div></div>
		<div class="fb_shangchuan" style="margin-top: 10px; display: block;">
    	<div class="album" style="margin-bottom: 15px;"></div>
			<img src="/ppb/img/1_03.png"  class="clickpz" />
			<div id="change" style="width: 100%;height: 300px;position: fixed;bottom: 0;left: 0;z-index: 99;color: #fff;text-align: center;line-height: 50px;font-size: 18px;display: none;">
				<div class="closepage" style="height: 200px;border-bottom: 1px solid #fff;background:color;"></div>
				<div id="phone" style="height: 50px;border-bottom: 1px solid #fff;background: #f15105;">拍照上传</div>
				<div id="picture" style="background: #f15105;">图库选择</div>
			</div>
            <input id="changeimg" type="file" name="img[]" onchange="previewImage(this,5)"  style="display: none">
			<input id="takepicture" type="file" name="img[]"  accept="image/*" capture="camera" style="display: none">
			<input type="button" style="margin-bottom: 30px;display: block;border: 1px solid;border-radius: 5px;background-color: #fff; height: 26px;display: none;" class="quedingsc"  value="确定上传" data="1" />
		</div>

</div>
<input type="text" hidden="hidden" value="<?php echo $oid; ?>" name="order_id" class="shangcimg"></input>
</form>
 <footer>
  			<?php if($order['hg_status'] != '审核合格'): ?>
            	 <button class="one">合格</button>
                 <button class="two">驳回</button>
             <?php endif; ?>
</footer>
</body>
<script src="/ppb/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="/ppb/js/formDate.js"></script>
<!-- <script src="/ppb/jquery.min.js"></script> -->
<script src="/ppb/jquery.mousewheel.min.js"></script>
 <script src="/ppb/pictureViewer.js"></script>
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


$("body").on("click",".quedingsc",function(){
	if ($(this).attr('data')==1) {
			alert("请先拍摄图片")
          }else{
          	
          	// $(".shangcimg").val(<?php echo $oid; ?>);
          	$("#formLogin").submit();
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
        }
        return false;
    });

//	合格
	$("body").on("click",".one",function(){
		$.ajax({
		type:"get",
		url:"http://ppb.dhxdrawing.top/index.php/ppb/admin/qualityInspectionResult",
		dataType: 'JSON', //数据格式:JSON
		data: {order_id:<?php echo $oid; ?>,admin_id:<?php echo $admin; ?>,type:1},
		success: function(data) {
			if (data.status==0) {
				alert(data.msg);
			} else{
				alert("提交成功")
				location.reload();
			}
		}
	})
	})
	//卜合格
	$("body").on("click",".two",function(){
		var judge = prompt("请输入驳回理由");
            if (judge) {
//              document.write("输入的内容为："+judge+"。");
                $.ajax({
					type:"get",
					url:"http://ppb.dhxdrawing.top/index.php/ppb/admin/qualityInspectionResult",
					dataType: 'JSON', //数据格式:JSON
					data: {order_id:<?php echo $oid; ?>,admin_id:<?php echo $admin; ?>,type:0,msg:judge},
					success: function(data) {
						if (data.status==0) {
							alert(data.msg);
						} else{
							alert("提交成功")
							location.reload();
						}
					}
				})
            } else {
             
            }
	})
	//提交快递单号
	$("body").on("click",".quedtjj",function(){
		var code=$(".kdhm").val();
			$.ajax({
				type:"get",
				url:"http://ppb.dhxdrawing.top/index.php/ppb/admin/shippingcode",
				dataType: 'JSON', //数据格式:JSON
				data: {
					code:code,
					order_id:<?php echo $oid; ?>
				},
				success: function(data) {
					if (data.status==1) {
						alert("操作成功");
						location.reload();
					}else{
						alert(data.msg);
					}
				}
			})


})
	//图片放大
		$('body').on('click','.sc1 img,.sc2 img,.sc3 img', function () {
        var this_ = $(this);
        var images = this_.parents('.yishangchuan,.yixuanze,.zhijian').find('div');
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
</script>
</html>