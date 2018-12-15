<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:46:"./application/admin/view2/order\ajaxindex.html";i:1540884577;}*/ ?>
<table>
 	<tbody>
 	<?php if(empty($orderList) == true): ?>
 		<tr data-id="0">
	        <td class="no-data" align="center" axis="col0" colspan="50">
	        	<i class="fa fa-exclamation-circle"></i>没有符合条件的记录
	        </td>
	     </tr>
	<?php else: if(is_array($orderList) || $orderList instanceof \think\Collection || $orderList instanceof \think\Paginator): $i = 0; $__LIST__ = $orderList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
  	<tr data-order-id="<?php echo $list['order_id']; ?>" id="<?php echo $list['order_id']; ?>">
        <td class="sign" axis="col0">
          <div style="width: 24px;"><i class="ico-check"></i></div>
        </td>
        <td align="center" abbr="order_id" axis="col3" class="">
            <div style="text-align: center; width: 40px;" class=""><?php echo $list['order_id']; ?></div>
        </td>
        <td align="center" abbr="order_sn" axis="col3" class="">
          <div style="text-align: center; width: 140px;" class=""><?php echo $list['order_sn']; ?></div>
        </td>
        <td align="center" abbr="consignee" axis="col4" class="">
          <div style="text-align: center; width: 100px;" class=""><?php echo $list['consignee']; ?></div>
        </td>
        <td align="center" abbr="consignee" axis="col4" class="">
          <div style="text-align: center; width: 150px;" class=""><?php echo $list['mobile_name']; ?>-<?php echo $list['attr_name']; ?>-<?php echo $list['brand_name']; ?></div>
        </td>
        <td align="center" abbr="consignee" axis="col4" class="">
          <div style="text-align: center; width: 100px;" class=""><?php echo $list['mobile']; ?></div>
        </td>
        <td align="center" abbr="article_time" axis="col6" class="">
            <div style="text-align: center; width: 100px;" class=""><?php echo $order_status[$list[order_status]]; ?></div>
        </td>
        <td align="center" abbr="article_time" axis="col6" class="">
          <div style="text-align: center; width:200px;" class=""><?php echo $list['province']; ?><?php echo $list['city']; ?><?php echo $list['twon']; ?><?php echo $list['address']; ?></div>
        </td>
        <td align="center" abbr="article_show" axis="col5" class="">
            <div style="text-align: center; width: 60px;" class="">
                <?php if(!empty($list['six_content'])): ?>
                <?php echo $list['six_content']; elseif(!empty($list['five_content'])): ?>
                <?php echo $list['five_content']; elseif(!empty($list['four_content'])): ?>
                <?php echo $list['four_content']; elseif(!empty($list['third_content'])): ?>
                <?php echo $list['third_content']; elseif(!empty($list['seacond_content'])): ?>
                <?php echo $list['seacond_content']; elseif(!empty($list['first_content'])): ?>
                <?php echo $list['first_content']; endif; ?>
            </div>
        </td>
        <td align="center" abbr="article_time" axis="col6" class="">
          <div style="text-align: center; width: 80px;" class=""><a style="width: 50px;height: 30px;display: inline-block;text-decoration: none;background: limegreen;line-height: 30px;text-align: center;color: #fff;font-weight: bold;border-radius: 5px;"><?php echo $list['adminName']; ?></a></div>
        </td>
        <!-- <td align="center" abbr="article_time" axis="col6" class="">
          <div style="text-align: center; width: 60px;" class=""><?php echo $list['goods_price']; ?></div>
        </td> -->
        <td align="center" abbr="article_time" axis="col6" class="">
          <div style="text-align: center; width: 120px;" class="">
          <?php echo date('Y-m-d H:i',$list['pay_time']); ?>
          </div>
        </td>
        <td align="center" abbr="article_time" axis="col6" class="">
          <div style="text-align: center; width: 120px;" class="">
          <?php echo date('Y-m-d H:i',$list['shipping_time']); ?>
          </div>
        </td>
        <td align="center" abbr="article_time" axis="col6" class="">
          <div style="text-align: center; width: 120px;" class="">
          <?php echo date('Y-m-d H:i',$list['add_time']); ?>
          </div>
        </td>
        <td align="left" axis="col1" class="handle" align="center">
        		<div style="text-align: left; ">
        			<a class="btn red" href="<?php echo U('Admin/order/detail',array('order_id'=>$list['order_id'])); ?>"><i class="fa fa-list-alt"></i>查看</a>
                    
                    <a class="btn blue" id="open" dataoid="<?php echo $list['order_id']; ?>" datasn="<?php echo $list['order_sn']; ?>" onclick="opendir(this)" href="javascript:void(0);"><i class="fa fa-list-alt"></i>分配客服</a>

                    <?php if($list['order_status'] == 0): ?>
                        <a class="btn green" onclick="submineOrder(this)" daOid="<?php echo $list['order_id']; ?>" href="javaScript:void(0);"><i class="fa fa-list-alt"></i>确认已取/已收到</a>
                    <?php endif; ?>
        		</div>
        </td>
		<td style="width: 100%;">
          <div></div>
        </td>
      </tr>
      <?php endforeach; endif; else: echo "" ;endif; endif; ?>
    </tbody>
</table>
<div id="fenpei" style="width: 30%;height: 250px;background: #fff;border-radius: 10px;border:1px solid #333;position: absolute;top: 10%;left: 30%;display: none;">
    <div style="width: 20px;height: 20px;font-size: 20px;font-weight: bold;float: right;cursor: pointer" id="guanbi" onclick="colse()">✘</div>
    <form action="" method="post" id="myform">
        <dl class="row" style="height: 50px;margin-top: 20px;padding-left: 50px;">
            <dt class="tit" style="width: 100%;">
                <label for="order_id">订单id</label>
            </dt>
            <dd class="opt">
                <input type="text" readonly name="order_id" id="order_id" value="" class="input-txt"/>
            </dd>
         </dl>
        <!--<dl class="row" style="height: 50px;margin-top: 5px;padding-left: 50px;">-->
            <!--<dt class="tit" style="width: 100%;">-->
                <!--<label for="order_sn">订单号</label>-->
            <!--</dt>-->
            <!--<dd class="opt">-->
                <!--<input type="text" readonly name="order_sn" id="order_sn" value="" class="input-txt"/>-->
            <!--</dd>-->
        <!--</dl>-->
        <dl class="row" style="position: absolute;right: 15%;top: 20px;">
            <dt class="tit" style="width: 100%;">
                <label for="order_sn">业务员</label>
            </dt>
            <dd class="opt">
                <select name="ywy_id">
                    <?php if(is_array($admin) || $admin instanceof \think\Collection || $admin instanceof \think\Paginator): if( count($admin)==0 ) : echo "" ;else: foreach($admin as $key=>$val): ?>
                        <option value="<?php echo $val['admin_id']; ?>"><?php echo $val['user_name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>

                </select>
            </dd>
        </dl>
        <a href="javascript:void(0);" onclick="submineYwy(this)" style="color: #fff;text-decoration: none;margin-top: 50px;margin-left: 40%;" class="ncap-btn-big ncap-btn-green" >提交分配</a>
    </form>
</div>
<div class="row">
    <div class="col-sm-6 text-left"></div>
    <div class="col-sm-6 text-right"><?php echo $page; ?></div>
</div>

<script>
    function colse(){
        $('#fenpei').hide();
    }
    function opendir(e){
        var oid=$(e).attr('dataoid');
        var sn=$(e).attr('datasn');
        $('#order_id').val(oid);
        $('#order_sn').val(sn);
        $('#fenpei').show();
    }
    function submineYwy(e){
        $.ajax({
            url:"<?php echo U('Admin/order/submineYwy'); ?>",
            type : "POST",
            data:$('#myform').serialize(),
            dataType:'json',
            async:false,
            success: function(data){
                if(data.status ==1){
                    layer.alert(data.msg, {icon: 1});
                    $('#fenpei').hide();
                    location.href=location.href;
                }else{
                    layer.alert(data.msg, {icon: 2});
                }
            },
            error:function(){
                layer.alert('网络异常，请稍后重试',{icon: 2});
            }
        })
    }
    function submineOrder(e){
        var oid=$(e).attr('daOid');
        $.ajax({
            url:"<?php echo U('Admin/order/submineOrder'); ?>",
            type : "POST",
            data:{order_id:oid},
            dataType:'json',
            async:false,
            success: function(data){
                if(data.status ==1){
                    layer.alert(data.msg, {icon: 1});
                    location.href=location.href;
                }else{
                    layer.alert(data.msg, {icon: 2});
                }
            },
            error:function(){
                layer.alert('网络异常，请稍后重试',{icon: 2});
            }
        })
    }

    $(".pagination  a").click(function(){
        var page = $(this).data('p');
        ajax_get_table('search-form2',page);
    });
    
 // 删除操作
    function del(obj) {
        layer.confirm('确定要删除吗?', function(){
            var id=$(obj).data('order-id');
            $.ajax({
                type : "POST",
                url: "<?php echo U('Admin/order/delete_order'); ?>",
                data:{order_id:id},
                dataType:'json',
                async:false,
                success: function(data){
                    if(data.status ==1){
                        layer.alert(data.msg, {icon: 1});
                        $('#'+id).remove();
                    }else{
                        layer.alert(data.msg, {icon: 2});
                    }
                },
                error:function(){
                    layer.alert('网络异常，请稍后重试',{icon: 2});
                }
            });
		});
	}
    
    $('.ftitle>h5').empty().html("(共<?php echo $pager->totalRows; ?>条记录)");
</script>