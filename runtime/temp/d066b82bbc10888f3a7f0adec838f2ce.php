<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:63:"E:\phpstudy\WWW\ppb/application/ppb\view\Admin\edit_order2.html";i:1538025412;}*/ ?>
<!DOCTYPE html>
<html class="ui-page-login">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>订单步骤二</title>
<style type="text/css">
    html, body {
        overflow: visible;
        background-color: darkseagreen;
        overflow: auto;
        margin: 0px;
    }
    .title{
        width: 100%;
        height: 40px;
        line-height: 40px;
        text-align: center;
        background: darkorange;
        color: #fff;
        font-weight: 600;
    }
    .back{
        cursor: pointer;
        width: 80px;
        height: 35px;
        display: inline-block;
        text-decoration: none;
        background: #0ba4da;
        line-height: 35px;
        text-align: center;
        color: #fff;
        font-weight: bold;
        border-radius: 5px;
        margin-left: 14%;
    }
    .submit{
        width: 80px;
        height: 35px;
        display: inline-block;
        text-decoration: none;
        background: #0ba4da;
        line-height: 35px;
        text-align: center;
        color: #fff;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
    }
    .name,.content,.consignee{
        width: 100%;
        height: 40px;
        display: inline-block;
        line-height: 40px;
        text-align: center;
    }
    input{
        width: 100%;
        height: 30px;
    }
    .adiv,.bdiv,.cdiv,.bot{
        width: 60%;
        height: 76px;
        margin-left: 20%;
        font-size: 14px;
    }
</style>
</head>
<body>
        <div class="item-title">
            <div class="title">修改订单信息</div>
        </div>
        <div style="margin-left: 33%;margin-top: 10px;">
            <img src="/public/qrcode/order_<?php echo $oid; ?>.jpg" />
        </div>
    <form class="form-horizontal" action="" id="order-add" method="post">
            <label for="name" class="name">订单信息</label>
            <text class="content"><?php echo $data['mobile_name']; ?>-<?php echo $data['attr_name']; ?>-<?php echo $data['brand_name']; ?></text>
                <div for="consignee" class="consignee">步骤二</div>
                <div class="adiv">
                    <input type="text" name="seacond_start_time" id="seacond_start_time" value="<?php echo $data['seacond_start_time']; ?>" class="input-txt" placeholder="步骤二开始时间" />
                    <p class="notic">开始时间,格式：2018/02/15</p>
                </div>
                
                <div class="bdiv">
                    <input type="text" name="seacond_end_time" id="seacond_end_time" value="<?php echo $data['seacond_end_time']; ?>" class="input-txt" placeholder="步骤二完成时间"/>
                    <p class="notic">完成时间,格式：2018/02/15</p>
                </div>
                
                <div class="cdiv">
                     <input type="text" name="seacond_content" id="seacond_content" value="<?php echo $data['seacond_content']; ?>" class="input-txt" placeholder="步骤二描述"/>
                    <p class="notic">描述内容，不超过10个字</p>
                </div>
               
                <input type="hidden" name="order_id" value="<?php echo $oid; ?>">
                <input type="hidden" name="role_id" value="<?php echo $rid; ?>">
                <div class="bot">
                    <a href="JavaScript:void(0);" onClick="checkSubmit()" class="submit">确认提交</a>
                    <a class="back" href="javascript:history.back();" >返回上级</a>
                </div>

    </form>
<script src="/public/js/jquery-1.8.2.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    function checkSubmit()
    {
        var val=$('#seacond_content').val().length;
        if(val>10){
            alert('描述超过10个字符')
        }else{
            $.ajax({
            url:"<?php echo U('ppb/Admin/orderHandle'); ?>",
            type:"post",
            data:$('#order-add').serialize(),
            dataType:'json',
            success:function(res){
                console.log(res);
                if(res.status==1){
                    alert(res.msg)
                    location.href=location.href;
                }else{
                    alert(res.msg)
                }
            },
            error:function(){

            }

        })
        }
    }
</script>
</body>
</html>