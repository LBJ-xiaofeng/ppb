<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:58:"E:\phpstudy\WWW\ppb/application/ppb\view\admin\wxScan.html";i:1540890047;}*/ ?>
<!DOCTYPE html>
<html class="ui-page-login">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>扫一扫</title>
		<style type="text/css">
			.sao_div1{
				text-align: center;
				margin-top: 20%;
			}
			.one{
				display: inline-block;
				width: 37%;

			}
			.two{
				display: inline-block;
				width: 45%;
					height: 20px;
			}
			.three{
				display: inline-block;
				width: 10%;
			}
			.all{
				margin-top: 50px;
			}
		</style>
	</head>
	<body style="background: #fff">
		 <div class="sao_div1">
		 	<img src="/public/images/sao.jpg" class="saos" id="scanQRCode">
		 </div>

	</body>
	<script src="/public/js/jquery-1.8.2.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript">
             wx.config({
			beta : true,
			debug :false,
			appId : '<?php echo $data['appId']; ?>',
			timestamp :'<?php echo $data['timestamp']; ?>',
			nonceStr :'<?php echo $data['nonceStr']; ?>',
			signature :'<?php echo $data['signature']; ?>',
			jsApiList :['checkJsApi', 'scanQRCode']
			})
wx.error(function(res) {

        alert("出错了：" + res.errMsg);//这个地方的好处就是wx.config配置错误，会弹出窗口哪里错误，然后根据微信文档查询即可。
    });
wx.ready(function() {
        wx.checkJsApi({
             jsApiList : ['scanQRCode'],
             success : function(res) {
             }
        });
    })
         $("#scanQRCode").click(function(){
           	 wx.scanQRCode({
                needResult : 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                scanType : [ "qrCode"], // 可以指定扫二维码还是一维码，默认二者都有
                success : function(res) {
                	var string=res.resultStr;
					var str_before = '<?php echo $rid; ?>';
					var arr=['1','10','11','12','13','14','15','17','19'];
					if(arr.indexOf(str_before)<0){
						alert('您的账号不可操作步骤');
					}else{
						$.ajax({
							url:"<?php echo U('ppb/admin/is_myorder'); ?>",
							type:"post",
							data:{order_id:string},
							dataType:"json",
							success:function(res){
								if(res.status==1){
									window.location.href='<?php echo url("ppb/Admin/edit_order"); ?>?order_id='+ string+'&role_id='+str_before;
								}else{
									alert('该订单不属于该账号');
								}
							},
							error:function(){

							}
					})
                }
            }
        })

    })

	</script>
</html>
