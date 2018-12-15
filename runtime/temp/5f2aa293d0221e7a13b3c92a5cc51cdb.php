<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:61:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\xiugaixinxi.html";i:1540523791;}*/ ?>
<!DOCTYPE html>
<html>

	<head>
		<title>修改个人资料</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta content="telephone=no" name="format-detection">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
		<link rel="stylesheet" type="text/css" href="/ppb/css/common.css">

		<style type="text/css">
			.xiugai {
				width: 100%;
				height: 100%;
				background-color: #ffbfc6;
				color: white;
				border: 0px;
				border-radius: 5px;
			}
			
			.bottm {
				width: 70%;
				height: 30px;
				margin: 47px auto;
			}
			
			.danxuan {
				float: right;
			}
			
			.inut {
				float: right;
				width: 30%;
				height: 30px;
				text-align: center;
				margin-top: 15px;
				margin-left: 10%;
				border: 1px solid darkgrey;
				border-radius: 5px;
				font-size: 15px !important;
			}
			
			.xb {
				float: right;
			}
			.checked{
				float: right;
				margin-right: 20px;
			}
			.checked input{
				margin-right: 10px;
			}
			.diqu{
				width: 70% !important;
				height: 30px !important;
				text-align: center;
				margin-top: 15px;
				margin-left: 10%!important;
				border-radius: 5px;
				
			}
			a {
				color: black;
			}
			input{
				border:none;
			}
			#st20
        {
            opacity:0;
            filter:alpha(opacity=0);
            height: 100px;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 9;
        }
        .paixue{
        	float: right;
        	margin-left: 10px;
        	    height: 55px;
    overflow: hidden;

        }
		</style>
	</head>

	<body>
		
  <form action="http://ppb.dhxdrawing.top/index.php/ppb/user/setinfo" method="post" enctype="multipart/form-data" id="formLogin"/>
		<div id="user_data">
			<ul class="basic">
				<li>
					<a class="data" href="javascript:void(0)">
						<span>头像</span>
					<ul class="paixue">

                 <div class="yanzRight" style="position: relative;z-index: 99999;">
                  <input style="margin-top:5px;float: left;" id="st20" name="head_pic" onchange="previewImage(this,5)" type="file" multiple="multiple"/>
                  <img src="/ppb/img/xj.png" id="imghead5" class="imgg">
                  
                  <span class="dui" id="imgOrder_dui" style="display: none;"></span>
                 </div>
              <div id="preview5" style="clear:both;">
                  <img src="" alt="" height="200" width="200" style="display:none;"/>
              </div>
             </ul>
						
                        <div class="clear"></div>
					</a>
				</li>
				<li>
					<a class="data"  href="javascript:void(0)">
						<span>昵称</span>

						<input type="" name="nickname" id="" class="inut"  />
						<div class="clear"></div>
					</a>
				</li>
				<li>
					<a class="data"  href="javascript:void(0)">
						<span>签名</span>

						<input type="" name="district" id="" class="inut"  />
						<div class="clear"></div>
					</a>
				</li>
				<li>
					<a class="data"  href="javascript:void(0)">
						<span>性别</span>
						
					

	                    <div style="float: right;">
	                    	<input type="radio" name="sex" id="" checked  value="0" />保密	
	<input type="radio" name="sex" id=""  value="1" />男	
	<input type="radio" name="sex" id=""  value="2" />女	
	                    </div>			
						<div class="clear"></div>
					</a>
				</li>
			

			</ul>
			<input type="hidden" name="user_id">
			<input type="hidden" name="token" value="asDFgtRewq">

			<div class="bottm">
				<button class="xiugai"> 确认修改</button>
			</div>
		</div>
</form>
<!--三级联动-->
<script type="text/javascript" src="/ppb/js/jquery.min.js"></script>
   
   <script src="/ppb/js/formDate.js" type="text/javascript"></script>
    <script type="text/javascript">
    	
    var b=1;
    	function previewImage(file,imgNum){
    
      b=2;

        var MAXWIDTH  = 200;
        var MAXHEIGHT = 200;
        var div = document.getElementById('preview'+imgNum);
        if (file.files && file.files[0])
        {
            div.innerHTML ='<img id=imghead'+imgNum+'>';
            var img = document.getElementById('imghead'+imgNum+'');
            img.onload = function(){
                var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
                img.width  =  rect.width;
                img.height =  rect.height;
                //         img.style.marginLeft = rect.left+'px';
                img.style.marginTop = 0+'px';
            }
            var reader = new FileReader();
            reader.onload = function(evt){
            	img.src = evt.target.result;
            	console.log(evt);
            }
            reader.readAsDataURL(file.files[0]);
        }
        else //
        {
            var sFilter='filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
            file.select();
            var src = document.selection.createRange().text;
            div.innerHTML = '<img id=imghead'+imgNum+'>';
            var img = document.getElementById('imghead2');
            img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
            var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
            div.innerHTML = "<div id=divhead"+imgNum+" style='width:"+rect.width+"px;height:"+rect.height+"px;margin-top:"+rect.top+"px;"+sFilter+src+"\"'></div>";
        }
    }
 
    	$("input[name='user_id']").val(localStorage.getItem("user_id"));
    	$(".xiugai").click(function(){
//  		console.log(b);
          var nick=$("input[name='nickname']").val();
          var sex=$("input[name='sex']").val();
          // var head_pic=$("input[name='head_pic']").val();
          var a=$(".inut");
          if(nick!==""||sex!==""||b!==1){
          	for(var i=0;i<a.length;i++){

          	  if($(a[i]).val()==""){
          	  	$(a[i]).removeAttr("name");
          	  }else{

          	  }
          	}
          	if(b==1){
             $("#st20").removeAttr("name");
          		}else{

          		}
          	 $("#formLogin").submit();
          
          	}else{
          		alert("您未作任何修改");
          		return false;
          	}
        
    	})
    	$("#formLogin").ajaxForm(function(data){
    		console.log(111);
    		   console.log(data);
               if(JSON.parse(data).status==1){
             alert("修改成功");
              window.location.href="<?php echo U('ppb/ppb/member'); ?>";
             } else{
                alert("网络错误11！");
             }
            
       });
    </script>
    
	</body>

</html>