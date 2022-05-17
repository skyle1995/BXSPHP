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
function GetDirFile($dir){
    if(!is_dir($dir)) return false;
    $arr = array();
    $data = scandir($dir);
    foreach ($data as $val){
        $temp = $dir.DIRECTORY_SEPARATOR.$val;
        if(is_dir($temp) && $val!='.' && $val != '..'){
            // echo '目录：'.$temp.'<br>';
            GetDirFile($temp);
        }else{
            if($val != '.' && $val != '..'){
                $cursor = count($arr);
                $arr[$cursor]["file"] = $val;
                $arr[$cursor]["dir"] = $dir;
            }
        }
    }
    return $arr;
}

/**
 * 文本Json转数组
 * @param $str 转换内容
 * @param $obj 返回数组
 * @return 转换后的文本内容
*/
function json_arr($str,$obj=true){
    $str = str_replace("\\", "",$str);
    return json_decode($str,$obj);
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
 * 数组转json文本
 * @param $str 转换内容
 * @return 转换后的文本内容
*/
function json_msg($code="-1",$msg="未知系统异常",$error=false){
    exit(json_str(array("code"=>$code,"msg"=>$msg,"error"=>$error)));
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
    $out = array();
    foreach ($value as $val) {
        foreach ($arr as $k=>$v) {
            if($k!=$val){
                $out[$k] = $v;
            }
        }
        $arr = $out;
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
function randomFloat($min = 0, $max = 1, $length = "11") {
   	 $num = $min + mt_rand() / mt_getrandmax() * ($max - $min);
   	 return sprintf("%.".$length."f",$num);  //控制小数后几位
}

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
 * 取字符串中间
 * @param     $str          字符串
 * @param     $leftStr      左边文本
 * @param     $rightStr     右边文本
*/
function GetSubstr($str, $leftStr, $rightStr){
    $left = strpos($str, $leftStr);
    //echo '左边:'.$left;
    $right = strpos($str, $rightStr,$left);
    //echo '<br>右边:'.$right;
    if($left < 0 or $right < $left) return '';
    return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr));
}

/**
 * 跳转到页面
 * @param $time 等待时间 秒
 * @param $msg 显示内容
 * @return 获取到的内容
*/
function GoDomain($url,$time=0,$msg='正在跳转..'){
    echo "<div style='font-size:23px;font-family:微软雅黑;'>{$msg}</div>";
    echo "<meta http-equiv='Refresh' content='$time; url=$url' /> ";
    exit(0);
}

/**
 * 自动翻页
 * @param unknown $alg 总页数
 * @param unknown $page 当前页数
 * @param unknown $num 显示页数
 * @param unknown $module 附加参数
 */
function pagebar($alg, $page, $num, $module) {
    $first=1; //起始页
    $prev=$page-1; //上一页
    $next=$page+1; //下一页
    $last=$alg; //最后一页
    
    $num = min($alg, $num); // 处理显示的页码数大于总页数的情况
    if ($page > $alg || $page < 1){
        return; // 处理非法页号的情况
    }
    
    $end = $page + floor($num / 2) <= $alg ? $page + floor($num / 2) : $alg; // 计算结束页号
    $start = $end - $num + 1; // 计算开始页号
    
    if ($start < 1) { // 处理开始页号小于1的情况
        $end -= $start - 1;
        $start = 1;
    }
    
    if ($page>1)
    {
        echo '<li><a href="?page='.$first.$module.'">首页</a></li>';
        echo '<li><a href="?page='.$prev.$module.'">&laquo;</a></li>';
    } else {
        echo '<li class="disabled"><a>首页</a></li>';
        echo '<li class="disabled"><a>&laquo;</a></li>';
    }
    
    for ($i = $start; $i <= $end; $i ++) { // 输出分页条，请自行添加链接样式
        if ($i == $page){
            // echo '<li><a href="?page='.$i.$module.'">'.$i.'</a></li>';
            echo '<li class="disabled"><a>'.$i.'</a></li>';
        }else{
            echo '<li><a href="?page='.$i.$module.'">'.$i.'</a></li>';
            // echo '<li class="disabled"><a>'.$i.'</a></li>';
        }
    }
    
    if ($page<$alg) {
        echo '<li><a href="?page='.$next.$module.'">&raquo;</a></li>';
        echo '<li><a href="?page='.$last.$module.'">尾页</a></li>';
    } else {
        echo '<li class="disabled"><a>&raquo;</a></li>';
        echo '<li class="disabled"><a>尾页</a></li>';
    }
}

/**
 * 404信息提示界面
 * @param $msg      显示内容
 * @param $title    页面标题
 * @param $footer   底部代码
*/
function sysmsg($msg = '很抱歉，你访问的页面找不到了',$title = '系统提醒',$footer = "404") {
?><!DOCTYPE html>
<html lang="zh-CN" class="html-body-404">
<head>
    <title><?php echo $title; ?></title>
    <link href='/assets/img/favicon.ico' mce_href='/assets/img/favicon.ico' rel='icon' type='image/x-icon'>
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
    <style type="text/css">
        .html-body-404 {
        	height:100%;
        	min-height:350px;
        	font-size:32px;
        	font-weight:500;
        	border:0;
        	margin:0;
        	padding:0;
        }
        
        /*布局背景*/
        .content {
        	height:100%;
        	position:relative;
        	z-index:1;
        	background-color:#d2e1ec;
        	background-image:linear-gradient(tobottom,#bbcfe10%,#e8f2f680%);
        	overflow:hidden;
        }
        
        /*显示内容*/
        .snow {
        	position:absolute;
        	top:0;
        	left:0;
        	pointer-events:none;
        	z-index:20;
        }
        
        /*显示文本*/
        .main-text {
        	text-align:center;
        	padding:20vh 20px 0;
        	line-height:2em;
        	font-size:3vh;
        	color:#5d7399;
        }
        
        /*消息标题*/
        .main-text-t {
        	font-size:45px;
        	line-height:48px;
        	margin:0;
        	padding:0;
        }
        /*消息内容*/
        .main-text-n {
        	font-size:18px;
        	line-height:48px;
        	margin:0;
        	padding:0;
        }
        
        /*框架 树*/
        .ground {
        	height:160px;
        	width:100%;
        	position:absolute;
        	bottom:0;
        	left:0;
        	background:#f6f9fa;
        	box-shadow:0 0 10px 10px #f6f9fa;
        }
        
        /*背景 树*/
        .ground:before,.ground:after {
        	content:'';
        	display:block;
        	width:250px;
        	height:250px;
        	position:absolute;
        	top:-62.5px;
        	z-index:-1;
        	background:transparent;
        	-webkit-transform:scaleX(0.2) rotate(45deg);
        	transform:scaleX(0.2) rotate(45deg);
        }
        /*树 左*/
        .ground:after {
        	left:50%;
        	margin-left:-166.66667px;
        	box-shadow:-340px 260px 15px #8193b2,-620px 580px 15px #8193b2,-900px 900px 15px #b0bccf,-1155px 1245px 15px #b4bed1,-1515px 1485px 15px #8193b2,-1755px 1845px 15px #8a9bb8,-2050px 2150px 15px #91a1bc,-2425px 2375px 15px #bac4d5,-2695px 2705px 15px #a1aec6,-3020px 2980px 15px #8193b2,-3315px 3285px 15px #94a3be,-3555px 3645px 15px #9aa9c2,-3910px 3890px 15px #b0bccf,-4180px 4220px 15px #bac4d5,-4535px 4465px 15px #a7b4c9,-4840px 4760px 15px #94a3be;
        }
        
        /*树 右*/
        .ground:before {
        	right:50%;
        	margin-right:-166.66667px;
        	box-shadow:325px -275px 15px #b4bed1,620px -580px 15px #adb9cd,925px -875px 15px #a1aec6,1220px -1180px 15px #b7c1d3,1545px -1455px 15px #7e90b0,1795px -1805px 15px #b0bccf,2080px -2120px 15px #b7c1d3,2395px -2405px 15px #8e9eba,2730px -2670px 15px #b7c1d3,2995px -3005px 15px #9dabc4,3285px -3315px 15px #a1aec6,3620px -3580px 15px #8193b2,3880px -3920px 15px #aab6cb,4225px -4175px 15px #9dabc4,4510px -4490px 15px #8e9eba,4785px -4815px 15px #a7b4c9;
        }
        
        /*框架 雪堆*/
        .mound {
        	margin-top:-80px;
        	font-weight:800;
        	font-size:180px;
        	text-align:center;
        	color:#dd4040;
        	pointer-events:none;
        }
        
        /*雪堆*/
        .mound:before {
        	content:'';
        	display:block;
        	width:720px;
        	height:280px;
        	position:absolute;
        	left:50%;
        	margin-left:-360px;
        	top:50px;
        	z-index:1;
        	border-radius:100%;
        	background-color:#e8f2f6;
        	background-image:linear-gradient(tobottom,#dee8f1,#f6f9fa60px);
        }
        
        /*脚印*/
        .mound:after {
        	content:'';
        	display:block;
        	width:28px;
        	height:6px;
        	position:absolute;
        	left:50%;
        	margin-left:-150px;
        	top:68px;
        	z-index:2;
        	background:#dd4040;
        	border-radius:100%;
        	-webkit-transform:rotate(-15deg);
        	transform:rotate(-15deg);
        	box-shadow:-56px 12px 0 1px #dd4040,-126px 6px 0 2px #dd4040,-196px 24px 0 3px #dd4040;
        }
        
        /*404角度*/
        .mound_text {
        	-webkit-transform:rotate(6deg);
        	transform:rotate(6deg);
        }
        
        /*铲子相关*/
        .mound_spade {
        	display:block;
        	width:35px;
        	height:30px;
        	position:absolute;
        	right:50%;
        	top:42%;
        	margin-right:-250px;
        	z-index:0;
        	-webkit-transform:rotate(35deg);
        	transform:rotate(35deg);
        	background:#dd4040;
        }
        .mound_spade:before,.mound_spade:after {
        	content:'';
        	display:block;
        	position:absolute;
        }
        .mound_spade:before {
        	width:40%;
        	height:30px;
        	bottom:98%;
        	left:50%;
        	margin-left:-20%;
        	background:#dd4040;
        }
        .mound_spade:after {
        	width:100%;
        	height:30px;
        	top:-55px;
        	left:0;
        	box-sizing:border-box;
        	border:10px solid #dd4040;
        	border-radius:4px 4px 20px 20px;
        }
    </style>
</head>
<body class="html-body-404">
<!-- 网页开始 -->
<div class="content">
    <canvas class="snow" id="snow" width="100%" height="100%"></canvas>
    <div class="main-text">
        <span class="main-text-t"><? echo $title ;?></span><br>
        
        <span class="main-text-n"><? echo $msg ;?></span>
        <!--<div class="main-text-a"><a href="#">< 返回 首页</a></div>-->
    </div>
    <div class="ground">
        <div class="mound">
            <div class="mound_text"><?echo $footer;?></div>
            <div class="mound_spade"></div>
        </div>
    </div>
</div>
<!-- 网页结束 -->
<script>
    document.title = '<?php echo $title; ?>';
    // var obj = document.getElementById('layuimini-content-page');
    // if(obj){
    //     obj.style.cssText = "height: calc(100% + 44px);";
    // }
</script>
<script>
    (function () {
        function ready(fn) {
            if (document.readyState != 'loading') {
                fn();
            } else {
                document.addEventListener('DOMContentLoaded', fn);
            }
        }

        function makeSnow(el) {
            var ctx = el.getContext('2d');
            var width = 0;
            var height = 0;
            var particles = [];

            var Particle = function () {
                this.x = this.y = this.dx = this.dy = 0;
                this.reset();
            }

            Particle.prototype.reset = function () {
                this.y = Math.random() * height;
                this.x = Math.random() * width;
                this.dx = (Math.random() * 1) - 0.5;
                this.dy = (Math.random() * 0.5) + 0.5;
            }

            function createParticles(count) {
                if (count != particles.length) {
                    particles = [];
                    for (var i = 0; i < count; i++) {
                        particles.push(new Particle());
                    }
                }
            }

            function onResize() {
                width = window.innerWidth;
                height = window.innerHeight;
                el.width = width;
                el.height = height;

                createParticles((width * height) / 10000);
            }

            function updateParticles() {
                ctx.clearRect(0, 0, width, height);
                ctx.fillStyle = '#f6f9fa';

                particles.forEach(function (particle) {
                    particle.y += particle.dy;
                    particle.x += particle.dx;

                    if (particle.y > height) {
                        particle.y = 0;
                    }

                    if (particle.x > width) {
                        particle.reset();
                        particle.y = 0;
                    }

                    ctx.beginPath();
                    ctx.arc(particle.x, particle.y, 5, 0, Math.PI * 2, false);
                    ctx.fill();
                });

                window.requestAnimationFrame(updateParticles);
            }

            onResize();
            updateParticles();
        }

        ready(function () {
            var canvas = document.getElementById('snow');
            makeSnow(canvas);
        });
    })();
</script>
</body>
</html><?php exit(0);
}

/**
 * 信息提示界面
 * @param $msg 显示内容
 * @param $title 页面标题
*/
function newsmsg($msg = '未知异常，请联系网站管理员处理！',$title = '系统提醒') {
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
    <title><?php echo $title; ?></title>
    <link href='/assets/img/favicon.ico' mce_href='/assets/img/favicon.ico' rel='icon' type='image/x-icon'>
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
    <style type="text/css">
        html{background:#eee}body{background:#fff;color:#333;font-family:"微软雅黑","Microsoft YaHei",sans-serif;margin:2em auto;padding:1em 2em;max-width:700px;-webkit-box-shadow:10px 10px 10px rgba(0,0,0,.13);box-shadow:10px 10px 10px rgba(0,0,0,.13);opacity:.8}h1{border-bottom:1px solid #dadada;clear:both;color:#666;font:24px "微软雅黑","Microsoft YaHei",,sans-serif;margin:30px 0 0 0;padding:0;padding-bottom:7px}#error-page{margin-top:50px}h1{text-align:center}h3{text-align:center}#error-page p{font-size:9px;line-height:1.5;margin:25px 0 20px}#error-page code{font-family:Consolas,Monaco,monospace}ul li{margin-bottom:10px;font-size:9px}a{color:#21759B;text-decoration:none;margin-top:-10px}a:hover{color:#D54E21}.button{background:#f7f7f7;border:1px solid #ccc;color:#555;display:inline-block;text-decoration:none;font-size:9px;line-height:26px;height:28px;margin:0;padding:0 10px 1px;cursor:pointer;-webkit-border-radius:3px;-webkit-appearance:none;border-radius:3px;white-space:nowrap;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;-webkit-box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);vertical-align:top}.button.button-large{height:29px;line-height:28px;padding:0 12px}.button:focus,.button:hover{background:#fafafa;border-color:#999;color:#222}.button:focus{-webkit-box-shadow:1px 1px 1px rgba(0,0,0,.2);box-shadow:1px 1px 1px rgba(0,0,0,.2)}.button:active{background:#eee;border-color:#999;color:#333;-webkit-box-shadow:inset 0 2px 5px -3px rgba(0,0,0,.5);box-shadow:inset 0 2px 5px -3px rgba(0,0,0,.5)}table{table-layout:auto;border:1px solid #333;empty-cells:show;border-collapse:collapse}th{padding:4px;border:1px solid #333;overflow:hidden;color:#333;background:#eee}td{padding:4px;border:1px solid #333;overflow:hidden;color:#333}
    </style>
</head>
<body>
<div id="error-page">
    <?php echo '<h1>'.$title.'</h1>';
    echo '<h3>'.$msg.'</h3>'; ?>
</div>
<script>
    document.title = '<?php echo $title; ?>';
</script>
</body>
</html>
<?php
    exit;
}
?>