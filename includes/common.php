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

// 设置系统编码
header("Content-type: text/html; charset=UTF-8");

// 设置应用时区
date_default_timezone_set("PRC");
// date_default_timezone_set("Asia/Shanghai");

if(defined('IN_CORRECT')) exit("系统常量定义失败，请检查配置是否正常！");
define('IN_CORRECT', true); // 用于判数据库配置是否正常

define('SYSTEM_PATH', dirname(__FILE__).'/'); // 系统路径
define('SYSTEM_ROOT', dirname(SYSTEM_PATH).'/'); // 系统根目录

define('CC_Defender',1);

include_once(SYSTEM_ROOT.'config.php');
include_once(SYSTEM_PATH."function.php");

if($error_reporting) error_reporting(0);//屏蔽所有错误信息

if (isset($system_close)) sysmsg($system_close,"系统提醒"); //停止运行

if(!$nosession) session_start(); //创建session对话

// 开始加载插件文件
foreach ($config['module'] as $key=>$val) {
    $plugin = SYSTEM_ROOT."module/{$key}.php";
    if(file_exists($plugin)){
        include_once($plugin);
    }
}
?>