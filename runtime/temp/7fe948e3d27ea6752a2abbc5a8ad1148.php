<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:55:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\yyxd2.html";i:1541390250;}*/ ?>
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
        
        .all{
            clear: both;
            overflow: hidden;
            padding: 13px 5%;
            margin-top: 30px;
        }
        .all p{
                font-size: 19px;
                font-weight: bold;
        }
        .yyxd_ul1{
            overflow: hidden;
        }
        .yyxd_ul1 li{
                padding: 10px 20px;
                border: 1px solid #D4D4D4;
                text-align: center;
                float: left;
                border-radius: 8px;
                margin-left: 5%;
                margin-top: 10px;
        }
        .yyxd_ul1 li.active{
                 background:#ffbfc6;
                 border:1px solid #ffbfc6;
                 color: #fff;
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
             .ccc{
              margin-bottom: 60px;
             }
</style>
</head>
<body>
<div class="ccc"  style="margin-bottom: 60px;">
	<div>
		  <div class="all">
			   <p class="zl"></p>
		  </div>
		  <ul class="yyxd_ul1">
		     
		  </ul>
    </div>

</div>
     <footer>
                 <button class="one xiayibu">下一步</button>
            </footer>
</body>
           
            <script type="text/javascript" src="/ppb/js/jquery.min.js"></script>
<script type="text/javascript">
	localStorage.removeItem('zhonglei');
	
	$.ajax({
		type:"get",
		url:"http://ppb.dhxdrawing.top/index.php/ppb/index/clothingCategories",
		dataType: 'JSON', //数据格式:JSON
		data: {id:localStorage.getItem('leixing')},
		async:false,
		success: function(data){
		var leixing="";
		var zlname=localStorage.getItem('yuyuename');
		$(".zl").html(zlname)
		for(var i=0; i<data.data.length;i++) {
			leixing+=`

				 <li class="type" id3="${data.data[i].goods_attr_id}">${data.data[i].attr_name}</li>
					     
			    </div>	
			`
			
		}
			$(".yyxd_ul1").html(leixing);
			
			$(".type").click(function(){
    $(this).addClass("active").siblings().removeClass("active");
	localStorage.removeItem('zhonglei');
    localStorage.setItem("zhonglei",$(this).attr("id3"));
    
   
})
		}
	});
		
	$("body").on("click",".xiayibu",function(){
	var leiid=localStorage.getItem('zhonglei');
	if (leiid ==null) {
		alert("请选择种类");
	} else{
		// alert(leiid)
		window.location.href = "<?php echo U('ppb/ppb/yyxd3'); ?>";
	
		
	}
})
</script>
</html>