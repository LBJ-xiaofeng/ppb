<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:63:"E:\phpstudy\WWW\ppb/application/ppb\view\admin\admin_Login.html";i:1537415761;}*/ ?>
<!DOCTYPE html>
<html class="ui-page-login">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>登录</title>
    <link rel="stylesheet" type="text/css" href="/public/loginZhuceZhaomima/fonts/iconfont.css"/>
    <link href="/public/loginZhuceZhaomima/css/mui.css" rel="stylesheet" />
    <script src="/public/loginZhuceZhaomima/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <style>
        .ui-page-login,
        body {
            width: 100%;
            height: 100%;
            margin: 0px;
            padding: 0px;
            background-color: #fff;
        }
        .mui-content {
            height: 100%;
        }

        .area {
            margin: 20px auto 0px auto;
        }

        .mui-input-group {
            margin-top: 10px;
        }

        .mui-input-group:first-child {
            margin-top: 20px;
        }

        .mui-input-group label {
            width: 22%;
        }

        .mui-input-row label~input,
        .mui-input-row label~select,
        .mui-input-row label~textarea {
            width: 78%;
        }

        .mui-checkbox input[type=checkbox],
        .mui-radio input[type=radio] {
            top: 6px;
        }

        .mui-content-padded {
            margin-top: 25px;
        }

        .mui-btn {
            padding: 10px;
        }

        .link-area {
            display: block;
            margin-top: 25px;
            text-align: center;
        }

        .spliter {
            color: #bbb;
            padding: 0px 8px;
        }

        .oauth-area {
            position: absolute;
            bottom: 20px;
            left: 0px;
            text-align: center;
            width: 100%;
            padding: 0px;
            margin: 0px;
        }

        .oauth-area .oauth-btn {
            display: inline-block;
            width: 50px;
            height: 50px;
            background-size: 30px 30px;
            background-position: center center;
            background-repeat: no-repeat;
            margin: 0px 20px;
            /*-webkit-filter: grayscale(100%); */
            border: solid 1px #ddd;
            border-radius: 25px;
        }

        .oauth-area .oauth-btn:active {
            border: solid 1px #aaa;
        }

        .oauth-area .oauth-btn.disabled {
            background-color: #ddd;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="/public/loginZhuceZhaomima/css/dengli2.css" />

</head>

<body>


<div class="boos-boos">
    <div class="boos">
        <div class="denglu-logo"><span style="display: block;width: 100%;text-align: center;font-size: 24px;font-weight: bold;">皮皮班</span><br/><span style="display: block;width: 100%;text-align: center;">操作员登录</span></div>
        <div class="sjh">
            <div class="sjh-div">
                <span class="sjh-div-span"><img src="/public/loginZhuceZhaomima/img/iphone.png" style="width: 100%;height: 100%; margin-left: 10px;"/></span>
                <input type="text" name=""  placeholder="请输入您的账号" class="tel" />
            </div>
        </div>
        <div class="yzm">
            <div class="yzm-div">
                <span class="yzm-div-span"><img src="/public/loginZhuceZhaomima/img/password.png" style="width: 100%;height: 100%; margin-left: 10px;"/></span>
                <input type="password" name=""  placeholder="请输入密码" class="pwd"/>
            </div>
        </div>
        <div class="but">
            <div class="div-but"><button id="submit" style="height: 30px;">立刻登录</button></div>
        </div>
    </div>

</div>
</div>
<script src='/public/loginZhuceZhaomima/js/jquery.min.js' type='text/javascript' charset='utf-8'></script>

<script type='text/javascript'>
    $('#submit').click(function(){
        var pwd=$('.pwd').val();
        var tel=$('.tel').val();
        if(pwd!==""&&tel!==""){
            $.ajax({
                type: "post", //用POST方式传输
                url: "<?php echo U('ppb/admin/adminLogin'); ?>", //目标地址
                dataType: "JSON", //数据格式:JSON
                data:{username:tel,password:pwd},
                success: function(data){
                    console.log(data);
                    if(data.status==1){
                        alert(data.msg);
                        window.location.href="<?php echo U('ppb/admin/wxScan'); ?>";
                    }else{
                        alert(data.msg);
                    }
                },
                error:function(data) {
                    alert('网络错误');
                }

            });
        }else{
            alert("请填写完整信息");
        }

    })

</script>

</body>

</html>