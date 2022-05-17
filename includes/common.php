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

// 加载其他内容
include_once(SYSTEM_ROOT.'other.php');
?>