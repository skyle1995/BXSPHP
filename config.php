<?php
$config = [
    // 数据库配置
    "sql_int" => [
        'dbms' => 'mysql', //数据库类型
        'host' => 'localhost', //数据库服务器
        'port' => 3306, //数据库端口
        'name' => '', //数据库名
        'user' => '', //数据库用户名
        'pass' => '', //数据库密码
        "charset" => "utf8", //数据库字节
    ],
    // 应用配置
    "bind" => [
        "moduel" => "index",  //默认应用
        "view" => "index",  //默认控制器
    ],
    // 模块配置
    "module" => [
        "alipay" => "用于处理易支付系统的模块",
        "compile" => "数据加密专用模块，字符串编码",
        "network" => "网络请求curl高级模式拓展模块",
        "dblibrary" => "数据库模块，必须优先加载！",
        "newtime" => "用于处理转换Y2K38漏洞的时间",
        "security" => "防CC跨站攻击模块,防止恶意请求",
        "txprotect" => "反腾讯网址安全检测模块",
    ],
    // 系统配置
    "system" => [
        // 系统运行时区
        "timezone" => "Asia/Shanghai",
        // 屏蔽所有错误
        "err_close" => false,
        // 是否停止站点
        "sys_close" => false,
        // 数据库注入过滤
        "sql_check" => true,
        // cookie过期时间
        "excookie" => 604800,
    ],
];

// 关闭session (根据需求设置 true/false)
if(!isset($nosession)) $nosession = false;

// 关闭反腾讯网址安全检测(模块) (根据设置 true/false)
if(!isset($notxprotect)) $notxprotect = false;

// 防CC跨站攻击(模块) (需要关闭$nosession才能生效 true/false)
define('CC_Defender',$nosession);
?>