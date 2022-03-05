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

// [ 基础函数库 ]


/**
 * SQL注入过滤字符串 
 * 递归方式的对变量中的特殊字符进行转义
 * @param unknown $value
 * @return unknown|multitype:
*/
function addslashes_deep($value,$htmlspecialchars = false) {
    if (empty($value)) {
        return $value;
    } else {
        if(is_array($value)) {
            foreach($value as $key => $v) {
                unset($value[$key]);
                if($htmlspecialchars==true) {
                 $key=addslashes(htmlspecialchars($key));
                } else {
                 $key=addslashes($key);
                }
                if(is_array($v)) {
                    $value[$key]=addslashes_deep($v);
                } else {
                    if($htmlspecialchars==true) {
                        $value[$key]=addslashes(htmlspecialchars($v));
                    }else{
                        $value[$key]=addslashes($v);
                    }
                }
            }
        } else {
            if($htmlspecialchars==true) {
                $value=addslashes(htmlspecialchars($value));
            } else {
                $value=addslashes($value);
            }
        }
        return $value;
    }
}

/**
 * SQL注入过滤字符串 
 * 递归方式的对变量中的特殊字符进行转义
 * @param unknown $value
 * @return unknown|multitype:
*/
function daddslashes($string, $force = 0, $strip = FALSE) {
    !defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
    if(!MAGIC_QUOTES_GPC || $force) {
        if(is_array($string)) {
            foreach($string as $key => $val) {
                $string[$key] = daddslashes($val, $force, $strip);
            }
        } else {
            $string = addslashes($strip ? stripslashes($string) : $string);
        }
    }
    return $string;
}

/**
 * 获取访问用户真实IP地址 
 * @return 访问用户IP地址
*/
function real_ip(){
    $ip = $_SERVER['REMOTE_ADDR'];
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    return $ip;
}

/**
 * 取URL中的host信息
 * @param $url 链接
 * return host信息
 */
function getdomain($url){
    $arr=parse_url($url);
    return $arr['host'];
}

/**
 * 取随机字符串 
 * @param $length 获取字符长度
 * @return 随机字符串
*/
function RandStr($length){
    //字符组合
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $len = strlen($str)-1;
    $randstr = '';
    for ($i=0;$i<$length;$i++) {
    $num=mt_rand(0,$len);
    $randstr .= $str[$num];
    }
    return $randstr;
}

/**
 * 遍历指定目录下的文件
 * @param $path 目录路径
 * return 文件名数组
 */
function GetDirFile($path){
    if(!is_dir($path)){
        return false;
    }
    $arr = array();
    $data = scandir($path);
    foreach ($data as $value){
        if($value != '.' && $value != '..'){
            $arr[] = $value;
        }
    }
    return $arr;
}

/**
 * 文本Json转数组
 * @param $str 转换内容
 * @return 转换后的文本内容
*/
function json_arr($str){
    return json_decode(urldecode($str),true);
}

/**
 * 数组转json文本
 * @param $str 转换内容
 * @return 转换后的文本内容
*/
function json_str($arrays){
    return str_replace("\\/", "/",json_encode($arrays,JSON_UNESCAPED_UNICODE));
}

/**
 * 删除数组成员
 * @param $arr 为 原数组内容
 * @param $value 为 需要删除的对象(数组)
 * @return $array str
*/
function delByValue($arr, $value){
    if(!is_array($arr)){
        return $arr;
    }
    if(!is_array($value)){
        return $arr;
    }
    $tmp = array();
    for($i=0;$i<count($value);$i++){
        foreach ($arr as $key=>$val) {
            if($key!=$value[$i]){
                $tmp[$key] = $val;
            }
        }
        $arr = $tmp;
    }
    return $arr;
}

/**
 * 识别是否有中文
 * @param unknown $string   需要检测的文本串
 * @return value            0没有/1全部/2包含
 */
function checkChinese($string){
    if (preg_match("/^[\x7f-\xff]+$/",$string)){
        return 1;
    }
    if (preg_match("/[\x7f-\xff]/", $string)) {  //判断字符串中是否有中文
        return 2;
    }
    return 0;
}

/**
 * 生成随机小数
 * @param $min      最小数
 * @param $max      最大数
 * @param $length   取小数位数
 * @return 返回生成的小数
*/
function randomFloat($min = 0, $max = 1, $length = "16") {
   	 $num = $min + mt_rand() / mt_getrandmax() * ($max - $min);
   	 return sprintf("%.".$length."f",$num);  //控制小数后几位
}


// ‘r'	只读方式打开，将文件指针指向文件头。
// ‘r+'	读写方式打开，将文件指针指向文件头。
// ‘w'	写入方式打开，将文件指针指向文件头并将文件大小截为零。如果文件不存在则尝试创建之。
// ‘w+'	读写方式打开，将文件指针指向文件头并将文件大小截为零。如果文件不存在则尝试创建之。
// ‘a'	写入方式打开，将文件指针指向文件末尾。如果文件不存在则尝试创建之。
// ‘a+'	读写方式打开，将文件指针指向文件末尾。如果文件不存在则尝试创建之。
// -------------------------------------------------------------------------------------------
// ‘x'	创建并以写入方式打开，将文件指针指向文件头。如果文件已存在，则 fopen() 调用失败并返回 FALSE，并生成一条 E_WARNING 级别的错误信息。
// 如果文件不存在则尝试创建之。这和给 底层的 open(2) 系统调用指定 O_EXCL|O_CREAT 标记是等价的。此选项被 PHP 4.3.2 以及以后的版本所支持，仅能用于本地文件。
// -------------------------------------------------------------------------------------------
// ‘x+'	创建并以读写方式打开，将文件指针指向文件头。如果文件已存在，则 fopen() 调用失败并返回 FALSE，并生成一条 E_WARNING 级别的错误信息。
// 如果文件不存在则尝试创建之。这和给 底层的 open(2) 系统调用指定 O_EXCL|O_CREAT 标记是等价的。此选项被 PHP 4.3.2 以及以后的版本所支持，仅能用于本地文件。


/**
 * 写入文件
 * $file 文件完全名
 * $cont 写入内容
 * $type 写入方式
*/
function WriteFiles($file,$cont,$type="a"){
    if(file_exists($file)){
        //"当前目录中，文件存在"，追加
        $myfile = fopen($file, $type) or die("Unable to open file!");
        fwrite($myfile, $cont);
        //记得关闭流
        fclose($myfile);
    }else{
        //"当前目录中，文件不存在",新写入
        $myfile = fopen($file, "w") or die("Unable to open file!");
        fwrite($myfile, $cont);
        //记得关闭流
        fclose($myfile);
    }
}

/**
 * 读取文件
 * $file 文件完全名
 * return 成功返回内容 失败返回 null
*/
function ReadFiles($file){
    $str = null;
    if(file_exists($file)){
        $fp = fopen($file,"r");
        $str = fread($fp,filesize($file));//指定读取大小，这里把整个文件内容读取出来
        fclose($fp);
    }
    return $str;
}

/**
 * 跳转到页面
 * @param $time 等待时间 秒
 * @param $msg 显示内容
 * @return 获取到的内容
*/
function GoURL($url,$time,$msg='正在跳转..'){
    echo "<div style='font-size:23px;font-family:微软雅黑;'>{$msg}</div>";
    echo "<meta http-equiv='Refresh' content='$time; url=$url' /> ";
    die;
}

/**
 * 信息提示界面
 * @param $msg 显示内容
 * @param $title 页面标题
*/
function sysmsg($msg = '未知异常，请联系网站管理员处理！',$title = '站点提醒') {
    ?>    
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Language" content="zh-cn">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
        <meta http-equiv="Expires" content="0">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Cache-control" content="no-cache">
        <meta http-equiv="Cache" content="no-cache">
        <meta name="apple-mobile-web-app-capable" content="no">
        <meta name="apple-touch-fullscreen" content="yes">
        <meta name="format-detection" content="telephone=no,email=no">
        <meta name="apple-mobile-web-app-status-bar-style" content="white">
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title><?php echo $title; ?></title>
        <link href='/assets/img/favicon.ico' mce_href='/assets/img/favicon.ico' rel='icon' type='image/x-icon'>
        <style type="text/css">
            html{background:#eee}body{background:#fff;color:#333;font-family:"微软雅黑","Microsoft YaHei",sans-serif;margin:2em auto;padding:1em 2em;max-width:700px;-webkit-box-shadow:10px 10px 10px rgba(0,0,0,.13);box-shadow:10px 10px 10px rgba(0,0,0,.13);opacity:.8}h1{border-bottom:1px solid #dadada;clear:both;color:#666;font:24px "微软雅黑","Microsoft YaHei",,sans-serif;margin:30px 0 0 0;padding:0;padding-bottom:7px}#error-page{margin-top:50px}h1{text-align:center}h3{text-align:center}#error-page p{font-size:9px;line-height:1.5;margin:25px 0 20px}#error-page code{font-family:Consolas,Monaco,monospace}ul li{margin-bottom:10px;font-size:9px}a{color:#21759B;text-decoration:none;margin-top:-10px}a:hover{color:#D54E21}.button{background:#f7f7f7;border:1px solid #ccc;color:#555;display:inline-block;text-decoration:none;font-size:9px;line-height:26px;height:28px;margin:0;padding:0 10px 1px;cursor:pointer;-webkit-border-radius:3px;-webkit-appearance:none;border-radius:3px;white-space:nowrap;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;-webkit-box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);vertical-align:top}.button.button-large{height:29px;line-height:28px;padding:0 12px}.button:focus,.button:hover{background:#fafafa;border-color:#999;color:#222}.button:focus{-webkit-box-shadow:1px 1px 1px rgba(0,0,0,.2);box-shadow:1px 1px 1px rgba(0,0,0,.2)}.button:active{background:#eee;border-color:#999;color:#333;-webkit-box-shadow:inset 0 2px 5px -3px rgba(0,0,0,.5);box-shadow:inset 0 2px 5px -3px rgba(0,0,0,.5)}table{table-layout:auto;border:1px solid #333;empty-cells:show;border-collapse:collapse}th{padding:4px;border:1px solid #333;overflow:hidden;color:#333;background:#eee}td{padding:4px;border:1px solid #333;overflow:hidden;color:#333}
        </style>
    </head>
    <body id="error-page">
        <?php echo '<h1>'.$title.'</h1>';
        echo '<h3>'.$msg.'</h3>'; ?>
    </body>
    </html>
    <?php
    exit;
}
?>
