<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:57:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\tianjia.html";i:1539934012;}*/ ?>
﻿<!DOCTYPE html>
<html lang='zh-CN'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <title>添加收货地址</title>
    <meta name='keywords' content=''>
    <meta name='description' content=''>
    <script src='/ppb/static3/js/rem.js'></script>
    <script src='/ppb/static3/js/jquery.min.js' type='text/javascript'></script>
    <link rel='stylesheet' type='text/css' href='/ppb/static3/css/base.css'/>
    <link rel='stylesheet' type='text/css' href='/ppb/static3/css/page.css'/>
    <link rel='stylesheet' type='text/css' href='/ppb/static3/css/all.css'/>
    <!-- <link rel='stylesheet' type='text/css' href='/wash/static3/css/mui-bar.min.css'/> -->
    <link rel='stylesheet' type='text/css' href='/ppb/static3/css/loaders.min.css'/>
    <link rel='stylesheet' type='text/css' href='/ppb/static3/css/loading.css'/>
<style type='text/css'>
body,html{
	height: 100%;
}
	.mask{
		display: none;
		height: 100%;
		position: fixed;
		bottom: 0px;
		left: 0px;
		width: 100%;
		background: rgba(0,0,0,0.2);

	}
	.content{
		background: #fff;
		height: 70%;
		position: fixed;
		bottom: 0px;
		left: 0px;
		width: 100%;
	}
#select1{
	margin-left: 2%;
	    height: 20px;
    font-size: 12px;
        width: 25%;
    float: left;
        margin-right: 2%;
            margin-top: 10%;
}
#select2{
	    height: 20px;
    font-size: 12px;
        width: 25%;
    float: left;
        margin-right: 2%;
            margin-top: 10%;
}
#main {
    padding-top: 17px !important;
}
#select3{
	    height: 20px;
    font-size: 12px;
        width: 25%;
    float: left;
        margin-top: 10%;
}
.close{
	        float: right;
    font-size: 13px;
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #8c8c8c;
    text-align: center;
    line-height: 20px;
    margin-right: 10px;
    margin-top: 7px;
}
.tel{
	    font-size: .45rem;
    color: #bcbcbc;
    height: 1rem;
    padding: 0;
    text-align: right;
    line-height: 1rem;
    margin-top: .2rem;
    width: 60%;
    border: none;

}
</style>
</head>
	<body>
	    <div id='main' class='mui-clearfix add-address mto'>
	    	<div class='plist clearfloat data'>
				<ul>
					<li class='clearfloat'>
						<a href='#'>
							<p class='fl'>收货人</p>
							<input type='text' class='fr shuru' name='' id='' value='' placeholder='输入名字' />
						</a>
					</li>
					<li class='clearfloat'>
						<a href='#'>
							<p class='fl'>联系电话</p>
							<input type='number' class='fr tel' name='' id='' value='' placeholder='输入电话' />
						</a>
					</li>
					<li class='clearfloat suozai'>
						<a href='#'>
							<p class='fl diqu'>所在地区</p>
							<i class='fr iconfont icon-right icon-qianjin'></i>
						</a>
					</li>
					
				</ul>
			</div>
			<textarea name='' rows='4' cols='' placeholder='请填写详细地址，不少于5个字' class='textare box-s'></textarea>
	   
	    </div>
	    <div class='mask'>
	    	<div class='content' style='height: 45%'>
	    		<span class='close'> X </span>
	    		<p style='font-size: 17px;text-align: center;padding: 10px;'>
	    			请选择地区
	    		</p>
	    		<hr>
	    		<select id='select1' style='-webkit-appearance: block' onchange='change()'>
	    			<option>请选择</option>
	    			
	    			
	    		</select>
	    		<select id='select2' style='-webkit-appearance: block' onchange='change1()'>
	    			<option>请选择</option>
	    			
	    			
	    		</select>
	    		<select id='select3' style='-webkit-appearance: block' onchange='change2()'>
	    			<option>请选择</option>
	    			
	    			
	    		</select>
	    	</div>
	    </div>
	     <footer class='page-footer fixed-footer' id='footer'>
	     	<a href='javascript:void(0)' class='address-add fl baocun'>
	     		保存
	     	</a>
	    </footer>
	</body>
	<script type='text/javascript' src='/ppb/static2/js/jquery-1.8.3.min.js' ></script>
	<script src='/ppb/static2/js/fastclick.js'></script>
	<script src='/ppb/static2/js/mui.min.js'></script>
	<script type='text/javascript' src='/ppb/static2/js/hmt.js' ></script>
	<script type='text/javascript' src='/ppb/static2/js/jquery.min.js'></script>
	<!--默认按钮-->
	<script type='text/javascript'>
	$('.toggle').click(function(e){
	
		var toggle = this;
		
		e.preventDefault();
	
		$(toggle).toggleClass('toggle--on').toggleClass('toggle--off').addClass('toggle--moving');
	
		setTimeout(function(){
			$(toggle).removeClass('toggle--moving');
		}, 200)
		
	});
 $('#select2').css('display','none');
  $('#select3').css('display','none');
	</script>

	<script type='text/javascript'>
		  $.ajax({
        type: 'get', 
        url: 'http://ppb.dhxdrawing.top/index.php/ppb/index/getaddress', //目标地址
        dataType: 'JSON', //数据格式:JSON
        data: {},
        success: function(data) {
      console.log(data);
      var html='';
      for(var i=0;i<data.data.length;i++){
      	html+="<option value='"+data.data[i].id+"'>"+data.data[i].name+"</option>";
      	
      }

      $('#select1').append(html);
        }
    })
		  function change(){
		  	 $('#select2').html('');
		  	var id1=$('#select1 option:selected').val()
		  
		  	 $('#select2').css('display','block');
		  	 $.ajax({
        type: 'get', 
        url: 'http://ppb.dhxdrawing.top/index.php/ppb/index/getRegion', //目标地址
        dataType: 'JSON', //数据格式:JSON
        data: {pid:id1,type:2},
        success: function(data) {
      console.log(data);
      var html='';
      html+=`
			<option>请选择</option>
      	`
      for(var i=0;i<data.length;i++){

      	html+="<option value='"+data[i].id+"'>"+data[i].name+"</option>"
      }

      $('#select2').append(html);
        }
    })
		  }

function change1(){
	  	 $('#select3').html('');
		  	var id1=$('#select2 option:selected').val()
		  
		  	 $('#select3').css('display','block');
		  	 $.ajax({
        type: 'get', 
         url: 'http://ppb.dhxdrawing.top/index.php/ppb/index/getRegion', //目标地址
        dataType: 'JSON', //数据格式:JSON
        data: {pid:id1,type:3},
        success: function(data) {
      console.log(data);
      var html='';
      html+=`
			<option>请选择</option>
      	`
      for(var i=0;i<data.length;i++){
      	html+="<option value='"+data[i].id+"'>"+data[i].name+"</option>"
      }

      $('#select3').append(html);
        }
    })
		  }
var id1s;
	var id2s;
	var id3s;
function change2(){
	// alert('aaa');
		  	var id1=$('#select1 option:selected').html();
		  	var id2=$('#select2 option:selected').html();
		  	var id3=$('#select3 option:selected').html();
		    id1s=$('#select1 option:selected').val();
	        id2s=$('#select2 option:selected').val();
			id3s=$('#select3 option:selected').val();
		  	$('.mask').css('display','none');
		  	$('.diqu').html(id1+' '+id2+' '+id3);
		  }
$('.suozai').click(function(){
	$('.mask').fadeIn(500);
	// $('.mask').css('display','block');
})
$('.close').click(function(){
$('.mask').fadeOut(500);
	// $('.mask').css('display','none');

})
      function oneValues(){
			var result;
			var url=window.location.search; //获取url中"?"符后的字串  
			if(url.indexOf("?")!=-1){
			result = url.substr(url.indexOf("=")+1);
			}
			return result;
			}
			var type=oneValues();
$('.baocun').click(function(){
	var shuru=$('.shuru').val();
	var tel=$('.tel').val();
	var text=$('.textare').val();
	if(shuru!==''&&tel!==''&&text!==''){
		 $.ajax({
        type: 'get', 
        url: 'http://ppb.dhxdrawing.top/index.php/ppb/user/add_address', //目标地址
        dataType: 'JSON', //数据格式:JSON
        data: {user_id:localStorage.getItem('user_id'),consignee:shuru,province:id1s,city:id2s,twon:id3s,address:text,is_dafault:0,mobile:tel},
        success: function(data) {
        		if(data.status==1){
        			alert("添加成功");
        			if(type==0){
        				window.location.href="<?php echo U('ppb/ppb/yyqj'); ?>";
        			}else{
        				window.location.href="<?php echo U('ppb/ppb/dizhi'); ?>";
        			}
        			
        		}else{
        			alert(data.msg);
        		}
        }
    })
	}else{
		alert('请将数据填写完整');
	}
})
	</script>
</html>
