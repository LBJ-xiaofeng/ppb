<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:55:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\index.html";i:1540878893;}*/ ?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset='UTF-8'>
		<meta name='viewport' content='width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no' />
		<title>皮皮班</title>
		<script src="/ppb/css/mui.min.js" type="text/javascript" charset="utf-8"></script>
		<link href='/ppb/css/mui.css' rel='stylesheet' />
		<link rel='stylesheet' type='text/css' href='/ppb/css/aui.css'/>
		 <link rel='stylesheet' type='text/css' href='/ppb/css/swiper.min.css'>
		<link rel='stylesheet' type='text/css' href='/ppb/css/style.css' />
		<script src='/ppb/js/jquery-1.8.2.min.js' type='text/javascript' charset='utf-8'></script>
		
		<style type="text/css">
		body{
            background-color: #fff;
        }
       .swiper-wrapper img{
       	display: block;
       }
       .jiu_ul1 li{
            list-style: none;
            width: 19%;
            font-size: 12px;
            text-align: center;
            float: left;
            margin-left: 5%;
            margin-top: 26px;
           
           }
           .jiu_ul1 li:nth-child(4n-3){
            clear: both;
            float: left;
           }
           .jiu_ul1 li img{
            display: inline-block;
            width: 51%;
           }
           .jiu_ul1{
            overflow: hidden;
           }
           .content p{
           	display: block;
  
overflow: hidden; 
  
text-overflow: ellipsis; 
    
-o-text-overflow: ellipsis;

white-space:nowrap;
   
width:200px;
      
height:24px;
 
display:block;
           }
           .medongxi{
           	 cursor: pointer;
           }
           .style_ul3{
           	 cursor: pointer;
           }
           .more{
           	cursor: pointer;
           }
		</style>
		</head>

	<body>
		

		
		<div class='top-boos'>
			
			<div id="slider" class="mui-slider" style=" height: 200px;">
			
		</div>



			 <ul class="jiu_ul1">
		       <li class="medongxi">
		           <img  class="jiu_ul1-0img" style="width: 35px;height: 35px;border-radius:50% ;">
		           <p class="jiu_ul1-0name"></p >
		       </li>
		      <li>  <a href="<?php echo U('ppb/ppb/fwfw'); ?>">
		           <img  class="jiu_ul1-1img" style="width: 35px;height: 35px;border-radius:50% ;">
		           <p class="jiu_ul1-1name"></p >
		      </a> </li>
		       <li> <a href="<?php echo U('ppb/ppb/fwjs'); ?>">
		           <img  class="jiu_ul1-2img" style="width: 35px;height: 35px;border-radius:50% ;">
		           <p class="jiu_ul1-2name"></p >
		       </a></li>
		        <li><a href="<?php echo U('ppb/ppb/fwzx'); ?>">
		           <img  class="jiu_ul1-3img" style="width: 35px;height: 35px;border-radius:50% ;">
		           <p class="jiu_ul1-3name"></p >
		       </a></li>
		       
		   </ul>

			 <p class="style_p1">
			 	
			 </p>
			 <ul class="style_ul2">

			 </ul>
			  <p class="style_p1">
			 	<img src="/ppb/image/index(1).png">
			 </p>
			 <div class="all">
			 </div>
			 <p style="margin-bottom: 100px;text-align: center;" class="more"> 查看更多</p>
		<footer class='aui-bar aui-bar-tab aui-bar-tab-cl aui-border-t gddw' id='footer'>
			
			<a href="<?php echo U('ppb/ppb/index'); ?>" class='aui-bar-tab-item ' tapmode>
				<i><img src="/ppb/image/mine (7).jpg"></i>
				
			</a>
			
			<a class='aui-bar-tab-item' tapmode href="<?php echo U('ppb/ppb/daifahuo'); ?>">
				
				<i><img src="/ppb/image/mine (6).jpg"></i>
			</a>
				<a class='aui-bar-tab-item' tapmode href="<?php echo U('ppb/ppb/member'); ?>">
				
				<i><img src="/ppb/image/mine (5).jpg"></i>
			</a>
			
		
		</footer>

	</body>


</html> 
    <script src='/ppb/js/jquery.mousewheel.min.js'></script>
    <script type='text/javascript' src='/ppb/js/swiper.jquery.min.js'></script>
<script type="text/javascript">
	$("body").on("click",".medongxi",function(){
		if (localStorage.getItem('user_id')) {
			location.href="<?php echo U('ppb/ppb/medongxi'); ?>"
		} else{
			alert("请先登录");
			location.href="<?php echo U('ppb/ppb/login'); ?>";
			
		}
	})
			$.ajax({
	type: "get",
	url: 'http://ppb.dhxdrawing.top/index.php/ppb/index/index', //目标地址
	dataType: "JSON", //数据格式:JSON
	data: {
		
	},
	success: function(data) {
     console.log(data)
		var lunbo = '';
		var nav1="";
		var nav2="";
		var nav3="";
		var nav4="";
		var category0="";
		var category1="";
		

		var lunbo = '';

		lunbo += ("<div class=\"mui-slider-group mui-slider-loop lunbo\"><div class=\"mui-slider-item mui-slider-item-duplicate\"><a href=\"" + data.data.banner[0].ad_link + "\"><img src=\"" + data.data.banner[0].ad_code + "\"></a></div>");

		for(var i = 0; i < data.data.banner.length; i++) {

			lunbo += ("<div class=\"mui-slider-item\"><a href=\"" + data.data.banner[i].ad_link + "\"><img style=\"width:100%;height:200px;\" src=\"" + data.data.banner[i].ad_code + "\" ></a></div>");

		}

		lunbo += ("<div class=\"mui-slider-item mui-slider-item-duplicate\"><a href=\"" + data.data.banner[0].ad_link + "\"><img src=\"" + data.data.banner[0].ad_code + "\"></a></div></div><div class=\"mui-slider-indicator\">");

		for(var i = 0; i < data.data.banner.length; i++) {
			lunbo += "<div class=\"mui-indicator\"></div>";

		}

		lunbo += "</div></div>";

		$("#slider").html(lunbo);
		$(".mui-slider-indicator").first().addClass("mui-active");
		mui('.mui-slider').slider().gotoItem(0);
		mui("#slider").slider({
			interval: 2500
		});
		
	
			
			
		$(".jiu_ul1-0name").html(data.data.nav[0].name)
		$(".jiu_ul1-1name").html(data.data.nav[1].name)
		$(".jiu_ul1-2name").html(data.data.nav[2].name)
		$(".jiu_ul1-3name").html(data.data.nav[3].name)
		
		$(".jiu_ul1-0img").attr("src",data.data.nav[0].img)
		$(".jiu_ul1-1img").attr("src",data.data.nav[1].img)
		$(".jiu_ul1-2img").attr("src",data.data.nav[2].img)
		$(".jiu_ul1-3img").attr("src",data.data.nav[3].img)

		for(var i=0;i<data.data.category.length;i++){
			category0+=`
			<div style="text-align: center;margin-bottom: 20px;color: pink;width:100%;height:30px;">
			<div style="width: 24%;margin-left: 38%;border: 1px solid pink;">
			${data.data.category[i].mobile_name}
			</div>
			</div>
		`
			for(var j=0;j<data.data.category[i].child.length;j++){
			category0+=`
			<a style="display: inline-block;width: 40%;margin: 3%;height: auto;" class="yydd" ddid="${data.data.category[i].child[j].id}" data="${data.data.category[i].child[j].id}"><li style="display: inline-block;width: 100%;height: 100px;"><img style="width: 100%;height: 100px;" src="${data.data.category[i].child[j].image}"></li></a>
		`
			$(".style_ul2").html(category0);
			}
		}
		
	}
})

 var mySwiper = new Swiper ('.swiper-container', {
　　　　　　　　　　　　　　autoplay:2000,
　　　　　　　　pagination: '.swiper-pagination',　　　　　　　
　　　　　　　　paginationClickable:true,　　　　　　　
　　　　　　　　nextButton: '.swiper-button-next',
　　　　　　　　prevButton: '.swiper-button-prev',
　　　　　　})
 var page=1;
var number;
	$.ajax({
		type: 'get', 
		url: 'http://ppb.dhxdrawing.top/index.php/ppb/index/Information', //目标地址
		 async: false,//同步
		dataType: 'JSON', //数据格式:JSON
		data: {page:page,type:1},
		success: function(data) {
			number=data.data.countPage;
       var html='';
       var str='';
       for(var i=0;i<data.data.data.length;i++){
       	      html+=`
			 <ul class="style_ul3" data="${data.data.data[i].article_id}">
			 	<li><img src="${data.data.data[i].thumb}"></li>

			 	<li  class="content">
			 		<p>${data.data.data[i].title}</p>
			 		<p>${data.data.data[i].content}</p>
			 		<p>${data.data.data[i].add_time}</p>
			 	</li>
			 </ul>
       	      `

       }
       $(".all").html(html);


		}
	})
	$('.more').click(function(){
      page++;
        console.log(page,number);
    if(page>number){
      $('.more').html('已无更多数据');
    }else{
$.ajax({
		type: 'get', 
		url: 'http://ppb.dhxdrawing.top/index.php/ppb/index/Information', //目标地址
		 async: false,//同步
		dataType: 'JSON', //数据格式:JSON
		data: {page:page,type:1},
		success: function(data) {
			number=data.data.countPage;
       var html='';
      for(var i=0;i<data.data.data.length;i++){
       	      html+=`
			 <ul class="style_ul3" data="${data.data.data[i].article_id}">
			 	<li><img src="${data.data.data[i].thumb}"></li>
			 	<li>
			 		<p>${data.data.data[i].title}</p>
			 		<p>${data.data.data[i].author}</p>
			 		<p>${data.data.data[i].add_time}</p>
			 	</li>
			 </ul>
       	      `

       }
       $(".all").append(html);
         
		}
	})
    }
   
    
   })
	$("body").on("click",".style_ul3",function(){

		localStorage.setItem('detail_id',$(this).attr('data'));
		window.location.href="<?php echo U('ppb/ppb/detail'); ?>";
	})
	
	$("body").on("click",".yydd",function(){
		localStorage.removeItem("ddid");
		localStorage.setItem('ddid',$(this).attr('ddid'));
		localStorage.removeItem("indexid");
		localStorage.setItem('indexid',$(this).attr('data'));
		window.location.href="<?php echo U('ppb/ppb/ddxiang'); ?>";
	})
</script>
