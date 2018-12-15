<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 应用命名空间
    'app_namespace'          => 'app',
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT,APP_PATH.'function.php'],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'html',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => 'htmlspecialchars',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'ppb',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'ppb',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 路由使用完整匹配
    'route_complete_match'   => false,
    // 路由配置文件（支持配置多个）
    'route_config_file'      => ['route'],
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => false,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,


    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => [],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件 
    'exception_tmpl'         => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',
    // errorpage 错误页面
    'error_tmpl'         => THINK_PATH . 'tpl' . DS . 'think_error.tpl', 
    

    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH,
        // 日志记录级别
        'level' => [],
        // 日志开关  1 开启 0 关闭
        'switch' => 1,
    ],

    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace'                  => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------
/**/   
    'cache'                  => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => CACHE_PATH,
        // 缓存前缀
        'prefix' => 'e459d613186e6512ff8b849b0ac77b2e',
        // 缓存有效期 0表示永久缓存
        'expire' => 1,
    ],
   
/*   
    'cache'                  => [
        // 驱动方式
        'type'   => 'redis',        
        'host'       => '192.168.0.201', // 指定redis的地址  
    ],    
*/ 
    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'think',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],
    // 密码加密串
    'AUTH_CODE' => "TPSHOP",

    'ORDER_STATUS' =>[
        0 => '已下单',
        1 => '定损中',
        2 => '保养中',
       3 => '邮寄中',
       4 => '待评价',
       5 => '已完成',
    ],
//    'SHIPPING_STATUS' => array(
//        0 => '待发货',
//        1 => '已发货',
//        2 => '已收货',
//    ),
    'PAY_STATUS' => array(
        0 => '待付款',
        1 => '已付款',
//        2 => '部分支付',
//        3 => '已退款',
//        4 => '拒绝退款'
    ),
    'SEX' => [
        0 => '保密',
        1 => '男',
        2 => '女'
    ],
    'COUPON_TYPE' => [
    	0 => '下单赠送',
        1 => '指定发放',
        2 => '免费领取',
//        3 => '线下发放',
    ],
	'PROM_TYPE' => [
		0 => '默认',
		1 => '抢购',
		2 => '团购',
		3 => '优惠'			
	],
    'TEAM_FOUND_STATUS' => array(
        '0'=>'待开团',
        '1'=>'已开团',
        '2'=>'拼团成功',
        '3'=>'拼团失败',
    ),
    'TEAM_FOLLOW_STATUS' => array(
        '0'=>'待拼单',
        '1'=>'拼单成功',
        '2'=>'成团成功',
        '3'=>'成团失败',
    ),
    'TEAM_TYPE' => [0 => '分享团', 1 => '佣金团', 2 => '抽奖团'],
    // 订单用户端显示状态
    'WAITPAY'=>' AND pay_status = 0 AND order_status = 1 ', //订单查询状态 待支付
    'WAITSEND'=>' AND pay_status=1 AND shipping_status =0 AND order_status =2 ', //订单查询状态 待发货
    'WAITRECEIVE'=>' AND shipping_status=1 AND order_status = 3 ', //订单查询状态 待收货
    'WAITCCOMMENT'=> ' AND order_status=4 ', // 待评价 确认收货     //'FINISHED'=>'  AND order_status=1 ', //订单查询状态 已完成
    'FINISH'=> ' AND order_status = 5 ', // 已完成
    'CANCEL'=> ' AND order_status = 0 ', // 已取消
    'CANCELLED'=> 'AND order_status = 0 ',//已作废
    
    'ORDER_STATUS_DESC' => [
        'WAITPAY' => '待支付',
        'WAITSEND'=>'待发货',
        'PORTIONSEND'=>'部分发货',
        'WAITRECEIVE'=>'待收货',
        'WAITCCOMMENT'=> '待评价',
        'CANCEL'=> '已取消',
        'FINISH'=> '已完成', //
        'CANCELLED'=> '已作废'
    ],

    'REFUND_STATUS'=>array(
        -2 => '服务单取消',//会员取消
        -1 => '审核失败',//不同意
        0  => '待审核',//卖家审核
        1  => '审核通过',//同意
        2  => '买家发货',//买家发货
        3  => '已完成',//服务单完成
    ),
    /**
     * 售后类型
     */
    'RETURN_TYPE'=>array(
        0=>'仅退款',
        1=>'退货退款',
        2=>'换货',
    ),
    //短信使用场景
    'SEND_SCENE' => array(
        '1'=>array('用户注册','您的验证码为：${code}，您正在进行身份验证，如非本人操作，请忽略本短信，5分钟内有效！','regis_sms_enable'),
        '4'=>array('客户支付','亲爱的会员，您在${name}的订单提交成功。我们会尽快发货，记得关注我们的商城喔~感谢您的支持！','regis_sms_enable'),
        ),
    
    'APP_TOKEN_TIME' => 60 * 60 * 24 , //App保持token时间 , 此处为1天


    /*分页每页显示数*/
    'PAGESIZE' => 10,
    
    'WX_PAY2' => 1,


    'HTML_CACHE_ARR'=> [
    ['mca'=>'home_Goods_goodsInfo','p'=>['id']],  
    ['mca'=>'home_Index_index'],  // 缓存首页静态页面
    ['mca'=>'home_Goods_ajaxComment','p'=>['goods_id','commentType','p']],
    ['mca'=>'home_Goods_ajax_consult','p'=>['goods_id','consult_type','p']],
    ],

    /*订单操作*/
    'CONVERT_ACTION'=>[
        'pay'=> '付款',
        'pay_cancel'=>'取消付款',
        'confirm'=>'确认订单',
        'cancel'=>'取消确认',
        'invalid'=>'作废订单',
        'remove'=>'删除订单',
        'delivery'=>'确认发货',
        'delivery_confirm'=>'确认收货',
    ],
    'COUPON_USER_TYPE'=>['全店通用','指定商品可用','指定分类商品可用'],
    'image_upload_limit_size'=>1024 * 1024 * 5,//上传图片大小限制
//物流及预约取件配置
    'WULIU'=>[
        'EBusinessID'=>'1353439',
        'AppKey'=>'bb3cd546-af36-4e49-b57d-3a0478c9b734',
        'ReqURL'=>'http://testapi.kdniao.cc:8081/api/dist',
        'ReqURLTake'=>'http://testapi.kdniao.cc:8081/api/Eorderservice',
        'ReqURLOrder'=>'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx',
    ],
    //客如云
    'KERUYUNAPI'=>[
        'appKey'=>'0430dc48afb95e206be2acacf941d4ad',
        'shopIdenty'=>'810094162',
        'timestamp'=>time(),
        'version'=>'1.0',
        'token'=>'d2a1af7bd5e5477f0bc8a9930843425d',
    ]
];
