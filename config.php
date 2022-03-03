<?php
$config = [
    // 数据库配置
    "sql_int" => array(
        'dbms' => 'mysql', //数据库类型
        'host' => 'localhost', //数据库服务器
        'port' => 3306, //数据库端口
        'name' => '', //数据库名
        'user' => '', //数据库用户名
        'pass' => '', //数据库密码
        "charset" => "utf8", //数据库字节
    ),
    // 应用配置
    "bind" => array(
        "moduel" => "index",  //默认应用
        "controller" => "index",  //默认控制器
    ),
    // 模块配置
    "module" => array(
        "db_library" => "数据库模块，必须优先加载！",
        "db_extend" => "数据库插件拓展模块",
        // "curl" => "get或者post的curl请求模块",
        // "compile" => "数据加密专用模块",
        "newtime" => "用于处理转换Y2K38漏洞的时间",
        // "alipay" => "用于处理易支付系统的模块",
        "security" => "防CC跨站攻击模块",
        "txprotect" => "反腾讯网址安全检测模块",
        "other" => "其他内容处理模块，包含数据库调用，需要最后加载",
    ),
    
];
// $system_close = "网站开发中，暂时关闭访问！";  //取消注释则关闭站点

$error_reporting = false; // 屏蔽所有错误

//开启防御SQL注入功能，根据开发需求设置 true/false
$sql_check = true;

// 关闭session/防CC跨站攻击(模块) 根据开发需求设置 true/false
if(!isset($nosession)) $nosession = false;

// 关闭反腾讯网址安全检测(模块) 根据开发需求设置 true/false
if(!isset($notxprotect)) $notxprotect = false;
?>
