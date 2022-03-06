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

// [ 应用路由文件 ]

namespace bxs;

class framework
{
    static function http(){
        // 加载公共文件
        include_once("common.php");
        
        // 获取访问信息
        if(empty($_GET["s"])){
            $path = explode('/', ltrim($_SERVER['PATH_INFO'], "/"));
        }else{
            $path = explode('/', ltrim($_GET["s"], "/"));
        }
        
        $moduel = $path[0] ? $path[0] : $config['bind']['moduel']; // 模块
        $controller = $path[1] ? $path[1] : $config['bind']['controller']; // 控制器
        
        // 过滤掉多余的参数
        $_GET = delByValue($_GET,array('s'));
        
        // 判断应用是否存在
        if(!file_exists(APP_PATH."/{$moduel}/{$controller}.php")) {
            sysmsg("访问路径错误，请检查路径是否正确！","系统提醒");
        }
        
        // 设置模板文件路径
        define('__TEMP__', APP_PATH."/{$moduel}/");
        
        // 开始加载应用程序
        include_once(APP_PATH."/{$moduel}/{$controller}.php");
    }
};
?>
