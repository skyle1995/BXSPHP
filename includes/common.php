<?php
// +----------------------------------------------------------------------
// | BXSPHP [ WE CAN DO IT JUST BXS ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2019 http://i80k.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 百晓生(BXS) <skygoole88@gmail.com>
// +----------------------------------------------------------------------

// [ 应用公共文件 ]

// 设置输出编码
header("Content-type: text/html; charset=UTF-8");

// 处理路径相关
define('SYSTEM_PATH', dirname(__FILE__).'/'); // 系统路径
define('SYSTEM_ROOT', dirname(SYSTEM_PATH).'/'); // 系统根目录
define('APP_PATH',dirname(__DIR__).'/apps'); // 设置应用目录

// 加载配置文件
include_once(SYSTEM_ROOT.'config.php');
// 加载系统函数
include_once(SYSTEM_PATH."function.php");

// 定义系统变量
$system = $config["system"];

// 处理配置信息
if ($system["err_close"]) error_reporting(0);//屏蔽所有错误信息
if ($system["sys_close"]) sysmsg("站点管理员已停止系统运行！", "系统提醒"); //停止运行

// 设置应用时区
date_default_timezone_set($system["timezone"]);

// 处理静态变量
if(defined('IN_CORRECT')) exit("系统初始化失败！");
define('IN_CORRECT', true); // 用于判数据库配置是否正常

if(!$nosession) session_start(); //创建session对话

// 加载模块文件
foreach ($config['module'] as $key=>$val) {
    $plugin = SYSTEM_ROOT."module/{$key}.php";
    if(file_exists($plugin)){
        include_once($plugin);
    }
}

//过滤提交数据
if (!get_magic_quotes_gpc() && $$system["sql_check"] == true)
{
    if (!empty($_GET))
    {
        $_GET  = addslashes_deep($_GET);
    }
    if (!empty($_POST))
    {
        $_POST = addslashes_deep($_POST);
    }
    $_COOKIE   = addslashes_deep($_COOKIE);
    $_REQUEST  = addslashes_deep($_REQUEST);
}

//配置数据库信息
define('SQL_DBMS', $config['sql_int']['dbms']);
define('SQL_HOST', $config['sql_int']['host']);
define('SQL_NAME', $config['sql_int']['name']);
define('SQL_PORT', $config['sql_int']['port']);
define('SQL_USER', $config['sql_int']['user']);
define('SQL_PASS', $config['sql_int']['pass']);
define('SQL_CHARSET', $config['sql_int']['charset']);

if(!SQL_USER||!SQL_PASS||!SQL_NAME) sysmsg("请先配置config.php中的数据库信息！","系统提醒"); // 通过配置判断是否安装

$sql_tables = "pre_config"; // 站点配置数据表
$conf_db = M($sql_tables);

$conf = array();

// 通过数据库判断是否安装
if (count($conf_db->SQL("show tables like '{$sql_tables}'")[1]) == 0) {
    sysmsg("未找到数据表，请先将install.sql文件导入到数据库！","系统提醒");
}else{
    $row=$conf_db->select();
    if(is_array($row)){
        foreach ($row as $key=>$val) {
            $conf[$val["k"]] = $val["v"];
        }
    }else{
        sysmsg("未找到数据内容，请检查数据表信息！","系统提醒");
    }
};

define('LOGINS_HASH', "JR1V0Z9X");  // 混淆密钥
define('ENCRYPT_KEY', 'X-*GO^B3');  // 混淆密钥

$host = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST']; // 用户访问的地址

list($t1, $t2) = explode(' ', microtime());
$time_long = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);

$time = time(); // 当前时间戳
$date = date("Y-m-d H:i:s",$time); // 当前时间
$real_ip = real_ip(); // 用户ip地址
?>