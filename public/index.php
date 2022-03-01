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

// [ 应用入口文件 ]

// 设置应用目录
define('APP_PATH',__DIR__.'/../apps');
// define('APP_PATH',dirname(__FILE__).'/../apps'); // 另一种方法
// define('APP_PATH',dirname(__DIR__).'/apps'); // 另一种方法

// 加载公共文件
include_once(__DIR__.'/../includes/vendor.php');

// 执行HTTP应用并响应
bxs\framework::http();

// 亲^_^ 后面不需要任何代码了 就是如此简单
?>
