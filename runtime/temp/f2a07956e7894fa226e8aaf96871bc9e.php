<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:55:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\yyxd3.html";i:1541390267;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <title>预约下单</title>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no' />
    <style type="text/css">
        body,div,p,ul,li{
            padding: 0;
            margin: 0;
            list-style: none;
        }
       input{
        border:none;
       }
       .yy_div1{
        text-align: center;
        margin: 10px 0;

               }
               .yy_ul1{
                    overflow: hidden;
    line-height: 44px;
               }
               .yy_ul1 li{
                    overflow: hidden;
                  }
        .yy_div1 img{
                vertical-align: middle;
               }
        .yy_ul1 li span:first-child{
                  float: left;
                  margin-left: 5%;
               }
                .yy_ul1 li span:last-child{
                  float: right;
                  margin-right: 5%;
                  color: #ffbfc6;
                  display: none;
               }
               .yy_ul1 li span:last-child.active{
                display: block;
               }
footer{
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
        .one{  
                margin: 0;
                padding: 0;
                background-color: #ffbfc6;
                width: 100%;
                color: #fff;
                padding: 15px 0;
                border: none;
                font-size: 16px;
             }
</style>
</head>
<body>
<div class="yy_div1">
   <input style="width: 80%;height: 30px;border: 1px solid #eee;" type="" name="ssnr" placeholder="输入品牌名称查询">
 <img src="/ppb/image/ser.jpg" class="sousou">
</div>
<button class="back" style="width: 40px;height: 30px;border-radius:5px;font-size: 12px;background-color: #fff;display: none;margin: 10px;">back</button>

<ul class="yy_ul1"  style="margin-bottom: 60px;">
</ul>

   <footer>
                <button class="one xiayibu">添加</button>
            </footer>
</body>
 <script type="text/javascript" src="/ppb/js/jquery.min.js"></script>
<script type="text/javascript">

	 localStorage.removeItem('pinpaiid');
			    localStorage.removeItem('pinpainame');
	$.ajax({
		type:"get",
		url:"http://ppb.dhxdrawing.top/index.php/ppb/index/getBrands",
		dataType: 'JSON', //数据格式:JSON
		data: {attr_id:localStorage.getItem('leixing')},
		async:false,
		success: function(data){
		var leixing="";
		
		for(var i=0; i<data.data.length;i++) {
			leixing+=`

				   <li id4="${data.data[i].id}" pinpai="${data.data[i].name}">
					   <span>${data.data[i].name}</span>
					   <span class="spans">√</span>
				   </li>
			`
			
		}
			$(".yy_ul1").html(leixing);
			
			$(".yy_ul1 li").click(function(){
			    $(this).children(".spans").addClass("active");
			    $(this).siblings().children(".spans").removeClass("active");
			    localStorage.removeItem('pinpaiid');
			    localStorage.removeItem('pinpainame');
   				localStorage.setItem("pinpaiid",$(this).attr("id4"));
   				localStorage.setItem("pinpainame",$(this).attr("pinpai"));
			})
		}
	});
	
		$("body").on("click",".xiayibu",function(){
			var leiid=localStorage.getItem('pinpaiid');
			if (leiid ==null) {
				alert("请选择品牌");
			} else{
				// alert(leiid);
				
//				window.location.href = "yyxd4.html";
//				
				$.ajax({
					type:"get",
					url:"http://ppb.dhxdrawing.top/index.php/ppb/index/addMyData",
					async:true,
					data:{
						cat_id:localStorage.getItem('leixing'),
						attr_id:localStorage.getItem('zhonglei'),
						brand_id:localStorage.getItem('pinpaiid'),
						user_id:localStorage.getItem('user_id')
						},
					dataType:"json",
					success:function(data){
						if (data.msg==0) {
							alert(data.msg)
						} else{
							alert("添加成功")
							localStorage.removeItem('pinpaiid');
			    localStorage.removeItem('pinpainame');
							window.location.href = "<?php echo U('ppb/ppb/medongxi'); ?>";
						}
					}
				});
				
			}
		})
		
	//搜索
	$("body").on("click",".sousou",function(){
		var ssnr= $("input[name='ssnr']").val();
		$.ajax({
			type:"get",
			url:"http://ppb.dhxdrawing.top/index.php/ppb/index/searchBrands",
			dataType: 'JSON', //数据格式:JSON
			data: {attr_id:localStorage.getItem('leixing'),search:ssnr},
			async:false,
			success: function(data){
				console.log(data);
				var leixing="";
				if (data.status==0) {
					alert("暂无此品牌");
				} else{
					for(var i=0; i<data.data.length;i++) {
						leixing+=`
			
							   <li id4="${data.data[i].id}">
								   <span>${data.data[i].name}</span>
								   <span class="spans">√</span>
								</li>
						`	
					}
					$(".yy_ul1").html(leixing);
					$(".back").css("display","block");
					$("body").on("click",".back",function(){
						location.reload();
					})	
					$(".yy_ul1 li").click(function(){
					    $(this).children(".spans").addClass("active");
					    $(this).siblings().children(".spans").removeClass("active");
					    localStorage.removeItem('pinpaiid');
		   				localStorage.setItem("pinpaiid",$(this).attr("id4"));
					})
					
				}
				
			}
		
		})	
	})
</script>
</html>