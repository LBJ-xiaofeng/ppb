<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:57:"E:\phpstudy\WWW\ppb/application/ppb\view\ppb\gj_cxfj.html";i:1540893961;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<title>重新分拣</title>
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no' />
	<style type="text/css">
		body,div,p,ul,li{
			padding: 0;
			margin: 0;
			list-style: none;
		}
		
        footer{
        	position: fixed;
        	bottom: 0;
        	left: 0;
        	width: 100%;
        }
        .one{   margin: 0;
        		padding: 0;
        	    background-color: #FD9D13;
			    width: 100%;
			    color: #fff;
			    padding: 10px 0;
			    border: none;
			    font-size: 16px;
        }

        .all span{
            font-weight: bold;
        }
        .all{
            padding: 10px 5%;
        }
        .ming{
            display: block;
        }
        textarea{
         width: 100%;
         margin-top: 10px;
         font-weight: 100;
         border: 1px solid #E3E3E3;
            }
            .all>p{
            padding: 10px 0;
            font-size: 17px;
        }
        .gj_div2{
           
            height: 94px;
            background-color: white;
        }
        .gj_div2 ul{
            overflow: hidden;
        }
        .gj_div2 ul li{
            float: left;
            width: 30%;
            text-align: center;
            margin-left: 3%;
        }
        .gj_div2 ul li img{
            display: inline-block;
            width: 73%;
        }
        .gj_div2 ul li p{
            font-size: 14px;
        }
        .xuanzhong{
        	border: 1px solid #ffbfc6;
        	color: #ffbfc6;
        	font-weight: normal;
        }
        .start{
        	margin-bottom: 50px;
        }
</style>
</head>
<body>
	<div class="start">

</div>

          
            <footer>
            	 <button class="one wancheng">完成</button>
            </footer>
</body>
<script src="/ppb/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	 localStorage.removeItem('first_admin');
	  localStorage.removeItem('seacond_admin');
	  localStorage.removeItem('third_admin');
	   localStorage.removeItem('four_admin');
	   localStorage.removeItem('five_admin');
	   localStorage.removeItem('six_admin');
	  
	$("body").on("click",".xuan1",function(){
				    $(this).addClass("xuanzhong");
				    $(this).siblings().removeClass("xuanzhong");
				    localStorage.removeItem('first_admin')
				    localStorage.setItem("first_admin",$(this).attr('first_admin'))
	  
	})
	$("body").on("click",".xuan3",function(){
				    $(this).addClass("xuanzhong");
				    $(this).siblings().removeClass("xuanzhong");
				    localStorage.removeItem('third_admin')
				    localStorage.setItem("third_admin",$(this).attr('third_admin'))
	  
	})
	$("body").on("click",".xuan2",function(){
				    $(this).addClass("xuanzhong");
				    $(this).siblings().removeClass("xuanzhong");
				    localStorage.removeItem('seacond_admin')
				    localStorage.setItem("seacond_admin",$(this).attr('seacond_admin'))
	  
	})
	
	$("body").on("click",".xuan4",function(){
				    $(this).addClass("xuanzhong");
				    $(this).siblings().removeClass("xuanzhong");
				    localStorage.removeItem('four_admin')
				    localStorage.setItem("four_admin",$(this).attr('four_admin'))
	  
	})
	$("body").on("click",".xuan5",function(){
				    $(this).addClass("xuanzhong");
				    $(this).siblings().removeClass("xuanzhong");
				    localStorage.removeItem('five_admin')
				    localStorage.setItem("five_admin",$(this).attr('five_admin'))
	  
	})
	$("body").on("click",".xuan6",function(){
				    $(this).addClass("xuanzhong");
				    $(this).siblings().removeClass("xuanzhong");
				    localStorage.removeItem('six_admin')
				    localStorage.setItem("six_admin",$(this).attr('six_admin'))
	  
	})
	$("body").on("click",".wancheng",function(){
		$.ajax({
			type:"get",
			url:"http://ppb.dhxdrawing.top/index.php/ppb/admin/submitPhase",
			dataType: 'JSON', //数据格式:JSON
			data: {
				order_id:localStorage.getItem('gjddid'),
				first_admin:localStorage.getItem('first_admin'),
				seacond_admin:localStorage.getItem('seacond_admin'),
				third_admin:localStorage.getItem('third_admin'),
				four_admin:localStorage.getItem('four_admin'),
				five_admin:localStorage.getItem('five_admin'),
				six_admin:localStorage.getItem('six_admin')				
			},
			success: function(data) {
				if (data.status==0) {
					alert(data.msg);
				} else{
					alert(data.msg);
						
						localStorage.removeItem('first_admin');
					  localStorage.removeItem('seacond_admin');
					  localStorage.removeItem('third_admin');
					   localStorage.removeItem('four_admin');
					   localStorage.removeItem('five_admin');
					   localStorage.removeItem('six_admin');
					   window.history.go(-2);
				}
				
			}
		});
		
	})
	$.ajax({
	type:"get",
	url:"http://ppb.dhxdrawing.top/index.php/ppb/admin/phaseWork",
	dataType: 'JSON', //数据格式:JSON
	data: {},
	success: function(data) {
		jd1="";
		jd2="";
		jd3="";
		jd4="";
		jd5="";
		jd6="";
		
//第一阶段		
		if (data.data.firstWork.length==0) {
			
		} else{
			jd1+=`
			<div class="all">
						   <p>选择第一阶段分配的人员</p>
						</div>
						<div class="all">
						  <div class="gj_div2">
						    <ul>
			`
			for (var i=0;i<data.data.firstWork.length;i++) {
				jd1+=`
					
						        <li class="xuan1" first_admin="${data.data.firstWork[i].admin_id}"><img src="/ppb/image/tou.jpg"><p>${data.data.firstWork[i].user_name}</p></li>
				`	
			}
			jd1+=`
				 		</ul>
						</div>
						</div>
				`
			$(".start").html(jd1);
		}
//第二阶段		
		if (data.data.seacondWork.length==0) {
			
		} else{
			jd2+=`
			<div class="all">
						   <p>选择第二阶段分配的人员</p>
						</div>
						<div class="all">
						  <div class="gj_div2">
						    <ul>
			`
			for (var i=0;i<data.data.seacondWork.length;i++) {
				jd2+=`
					
					
				<li class="xuan2" seacond_admin="${data.data.seacondWork[i].admin_id}"><img src="/ppb/image/tou.jpg"><p>${data.data.seacondWork[i].user_name}</p></li>
				`	
			}
			jd2+=`
				 		</ul>
						</div>
						</div>
				`
			$(".start").append(jd2);
		}
		
//第3阶段		
		if (data.data.thirdWork.length==0) {
			
		} else{
			jd3+=`
			<div class="all">
						   <p>选择第三阶段分配的人员</p>
						</div>
						<div class="all">
						  <div class="gj_div2">
						    <ul>
			`
			for (var i=0;i<data.data.thirdWork.length;i++) {
				jd3+=`
					<li class="xuan3" third_admin="${data.data.thirdWork[i].admin_id}"><img src="/ppb/image/tou.jpg"><p>${data.data.thirdWork[i].user_name}</p></li>
					
				`	
			}
			jd3+=`
				 		</ul>
						</div>
						</div>
				`
			$(".start").append(jd3);
		}


//第4阶段		
		if (data.data.fourWork.length==0) {
			
		} else{
			jd4+=`
			<div class="all">
						   <p>选择第四阶段分配的人员</p>
						</div>
						<div class="all">
						  <div class="gj_div2">
						    <ul>
			`
			for (var i=0;i<data.data.fourWork.length;i++) {
				jd4+=`
					
					<li class="xuan4" four_admin="${data.data.fourWork[i].admin_id}"><img src="/ppb/image/tou.jpg"><p>${data.data.fourWork[i].user_name}</p></li>
				`	
			}
			jd4+=`
				 		</ul>
						</div>
						</div>
				`
			$(".start").append(jd4);
		}

//第5阶段		
		if (data.data.fiveWork.length==0) {
			
		} else{
			jd5+=`
			<div class="all">
						   <p>选择第五阶段分配的人员</p>
						</div>
						<div class="all">
						  <div class="gj_div2">
						    <ul>
			`
			for (var i=0;i<data.data.fiveWork.length;i++) {
				jd5+=`
					
					<li class="xuan5" five_admin="${data.data.fiveWork[i].admin_id}"><img src="/ppb/image/tou.jpg"><p>${data.data.fiveWork[i].user_name}</p></li>
				`	
			}
			jd5+=`
				 		</ul>
						</div>
						</div>
				`
			$(".start").append(jd5);
		}


//第6阶段		
		if (data.data.sixdWork.length==0) {
			
		} else{
			jd6+=`
			<div class="all">
						   <p>选择第六阶段分配的人员</p>
						</div>
						<div class="all">
						  <div class="gj_div2">
						    <ul>
			`
			for (var i=0;i<data.data.sixdWork.length;i++) {
				jd6+=`
					
					<li class="xuan6" six_admin="${data.data.sixdWork[i].admin_id}"><img src="/ppb/image/tou.jpg"><p>${data.data.sixdWork[i].user_name}</p></li>
				`	
			}
			jd6+=`
				 		</ul>
						</div>
						</div>
				`
			$(".start").append(jd6);
		}

	}
});
</script>
</html>