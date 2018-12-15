<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:59:"E:\phpstudy\WWW\ppb/application/ppb\view\Admin\fjorder.html";i:1541411922;}*/ ?>
<!DOCTYPE html>
<html class="ui-page-login">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/ppb/pictureViewer.css">
    <title>订单资料</title>
<style type="text/css">
    html, body {
        overflow: visible;
        background-color: darkseagreen;
        overflow: auto;
        margin: 0px;
    }
    .title{
        /*width: 100%;*/
        height: 40px;
        line-height: 40px;
        text-align: center;
        background: darkorange;
        color: #fff;
        font-weight: 600;
        border-bottom: 5px solid #fff;
    }
    .public{
    	width:90%;
    	height:100%;
    	padding-left: 10%;
    }
    .fpry,.start{
        width: 49%;
        height: 40px;
        background: red;
        line-height: 40px;
        color: #fff;
        text-align: center;
        font-weight: bold;
    }
    .active{
        margin-bottom: 10px;
    }
</style>

</head>
<body>
<div class="one">
        <div class="title"><?php echo $order['mobile_name']; ?>-<?php echo $order['attr_name']; ?>-<?php echo $order['brand_name']; ?></div>
        <div class="public">
        <div style="width: 90%;">
        <div>订单名称：<?php echo $order['mobile_name']; ?>-<?php echo $order['attr_name']; ?>-<?php echo $order['brand_name']; ?></div>
        <div>订单号码：<?php echo $order['order_sn']; ?></div>
        <div>订单状态：<?php echo $order['order_status']; ?></div>
        <div>支付状态：<?php echo $order['pay_status']; ?></div>
        <div>入库时间：<?php echo $order['pay_time']; ?></div>
        <div>下单时间：<?php echo date('Y-m-d H:i',$order['add_time']); ?></div>
    	</div>
   		</div>
</div>
<div class="one">
   		<div class="title">进度信息</div>
        <div class="public clicks">
        <div style="width: 90%;">
        <div>
        
        <?php if($order['first_end_time'] > 0): ?>
        <div class="active">第一阶段：<?php echo getAdmin($order['first_admin']); ?></div>
        <div class="active"><?php echo $order['first_start_time']; ?>--<?php echo $order['first_end_time']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $order['first_content']; ?></div>
        <?php else: ?>
        <div>暂无进程</div>
    	<?php endif; if($order['seacond_end_time'] > 0): ?>
        <div class="active">第二阶段：<?php echo getAdmin($order['seacond_admin']); ?></div>
        <div class="active"><?php echo $order['seacond_start_time']; ?>--<?php echo $order['seacond_end_time']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $order['seacond_content']; ?></div>
    	<?php endif; if($order['third_end_time'] > 0): ?>
        <div class="active">第三阶段：<?php echo getAdmin($order['third_admin']); ?></div>
        <div class="active"><?php echo $order['third_start_time']; ?>--<?php echo $order['third_end_time']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $order['third_content']; ?></div>
    	<?php endif; if($order['four_end_time'] > 0): ?>
        <div class="active">第四阶段：<?php echo getAdmin($order['four_admin']); ?></div>
        <div class="active"><?php echo $order['four_start_time']; ?>--<?php echo $order['four_end_time']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $order['four_content']; ?></div>
    	<?php endif; if($order['five_end_time'] > 0): ?>
        <div class="active">第五阶段：<?php echo getAdmin($order['five_admin']); ?></div>
        <div class="active"><?php echo $order['five_start_time']; ?>--<?php echo $order['five_end_time']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $order['five_content']; ?></div>
    	<?php endif; if($order['six_start_time'] > 0): ?>
        <div class="active">第六阶段：<?php echo getAdmin($order['six_admin']); ?></div>
        <div class="active"><?php echo $order['six_start_time']; ?>--<?php echo $order['six_end_time']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $order['six_content']; ?></div>
    	<?php endif; ?>
        </div>
    	</div>
   		</div>
</div>
    <?php if($order['first_admin'] == 0): ?>
    <div class="start" style="background: orange;position: fixed;bottom: 0;left:0;" onclick="kaishi(this)"  oid="<?php echo $order['order_id']; ?>" >开始工作</div>
    <?php elseif($order['wc_status'] != 1): ?>
    <div class="start" style="position: fixed;bottom: 0;left:0;" onclick="wancheng(this)"  oid="<?php echo $order['order_id']; ?>" >完成工作</div>
    <?php endif; if($order['wc_status'] != 1): ?>
    <div class="fpry" style="position: fixed;bottom: 0;left:52%;"  gjddid="<?php echo $order['order_id']; ?>" >分配人员</div>
     <?php endif; ?>
</body>
<script src="/public/js/jquery-1.8.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/ppb/jquery.mousewheel.min.js"></script>
 <script src="/ppb/pictureViewer.js"></script>
  <script type="text/javascript">
      $("body").on("click",".fpry",function(){
        localStorage.setItem("gjddid",$(this).attr('gjddid'))
        location.href="<?php echo U('ppb/ppb/gj_cxfj'); ?>";
    })
      function kaishi(e){
        var oid=$(e).attr('oid');
        $.ajax({
        type:"post",
        url:"http://ppb.dhxdrawing.top/index.php/ppb/admin/editOrder",
        dataType: 'JSON', //数据格式:JSON
        data: {order_id:oid},
        success: function(data) {
            if (data.status==0) {
                alert(data.msg);
            } else{
                alert(data.msg);
                location.href=location.href;
            }
        }
    });
      }
      function wancheng(e){
        var oid=$(e).attr('oid');
        $.ajax({
        type:"get",
        url:"http://ppb.dhxdrawing.top/index.php/ppb/admin/startWork",
        dataType: 'JSON', //数据格式:JSON
        data: {oid:oid},
        success: function(data) {
            if (data.status==0) {
                alert(data.msg);
            } else{
                alert(data.msg);
                location.href=location.href;
            }
        }
    });
      }
  </script>
</html>