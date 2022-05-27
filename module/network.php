<?php
// +----------------------------------------------------------------------
// | LingDian [ WE CAN DO IT JUST LINGDIAN ]
// +----------------------------------------------------------------------
// | Copyright (c) http://i80k.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 百晓生(BXS) <skygoole88@gmail.com>
// +----------------------------------------------------------------------

// curl访问处理模块

/**
 * 访问网页 高级
 * 参数为0则默认或不开启
 * @param     $url          请求网页地址
 * @param     $type         设置提交方式信息，支持类型 GET  POST  HEAD  PUT  OPTIONS  DELETE  TRACE  CONNECT
 * @param     $data         需要提交的POST数据（可自定义）。
 * @param     $cookie       在HTTP请求中提交的cookie（可自定义）。
 * @param     $httpheader   需要提交的header数据（可自定义）。
 * @param     $header       启用时会将头文件的信息作为数据流输出。
 * @param     $nobaody      启用时将不对HTML中的body部分进行输出。
 * @param     $encoding     HTTP请求头中"Accept-Encoding: "的值支持的编码有"identity"，"deflate"和"gzip"。如果为"all"，会发送所有支持的编码类型。
 * @param     $userAgent    在HTTP请求中提交的UserAgent信息（可自定义）。
 * @param     $referer      在HTTP请求头中"Referer:"的内容（可自定义）。
 * @return    获取到的内容
*/
function curl_senior($url, $type=0, $data=0, $cookie=0, $httpheader=0, $header=0, $nobaody=0,$encoding=0, $referer=0, $userAgent=0) {
    // 初始化请求
    $ch = curl_init();
    // 设置请求的url
    curl_setopt($ch, CURLOPT_URL, $url);
    // 设置提交方式信息 GET  POST  HEAD  PUT  OPTIONS  DELETE  TRACE  CONNECT
    if($type){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$type);
    }
    // 设置提交内容
    if($data){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    // 设置cookie信息
    if($cookie){
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    // 设置header信息
    if(!$httpheader or $httpheader == 0){
        $httpheader=[];
        $httpheader[] = "Accept: */*";
        $httpheader[] = "Connection: close";
        $httpheader[] = "Accept-Language: zh-CN,zh;q=0.8";
        $httpheader[] = "User-Agent: Mozilla/5.0 Chrome/99.0.4844.51 Safari/537.36 Edg/99.0.1150.36";
        $httpheader[] = "referer: ".$url;
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    // 启用时会将头文件的信息作为数据流输出
    if($header == 1){
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    }
    
    // 启用时将不对HTML中的body部分进行输出
    if($nobaody){
        curl_setopt($ch, CURLOPT_NOBODY,true);
    }
    // HTTP请求头中"Accept-Encoding: "的值，支持的编码有"identity"，"deflate"和"gzip"。如果为空字符串""，会发送所有支持的编码类型
    if($encoding){
        if($encoding == "all"){
            curl_setopt($ch, CURLOPT_ENCODING, "");
        }else{
            curl_setopt($ch, CURLOPT_ENCODING, $encoding);
        }
    }
    // 设置自定义User-Agent
    if($userAgent){
        curl_setopt($ch, CURLOPT_USERAGENT,$userAgent);
    }
    // 在HTTP请求头中"Referer:"的内容（可自定义）
    if($referer){
        if($referer==1){
            curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
        }else{
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }
    }
    // 忽略SSL安全性
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    // 设为true把curl_exec()结果转化为字串，而不是直接输出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // 开始请求
    $ret = curl_exec($ch);
    
    // 关闭URL请求
    curl_close($ch);
    
    // 返回结果
    return $ret;
}

/**
 * 获取header中的参数信息
 * @param     $header   未处理的header信息
 * @return    取出的header信息
*/
function getHeader($header,$name) {
    $ret = null;
    if($name == "Set-Cookie" or $name == "Cookie"){
        foreach (explode("\r\n",$header) as $val){
            if(strpos($val, ": ")){
                $obj = explode(": ",$val);
                if($obj[0] == "Set-Cookie" or $name == "Cookie"){
                    $ret = mergeCookie($ret ,$obj[1]);
                }
            }
        }
    }else{
        foreach (explode("\r\n",$header) as $val){
            if(strpos($val, ": ")){
                $obj = explode(": ",$val);
                if($obj[0] == $name){
                    $ret = $obj[1];
                }
            }
        }
    }
    return $ret;
}

/**
 * 获cookie中的参数信息
 * @param     $cookie   cookie信息
 * @return    取出的Cookie信息
*/
function getCookie($cookie,$name) {
    $ret = null;
    $cookie = str_replace("; ", ";", $cookie);
    foreach (explode(";",$cookie) as $val){
        if(strpos($val, "=")){
            $obj = explode("=",$val);
            if($obj[0] == $name){
                $ret = $obj[1];
            }
        }
    }
    return $ret;
}

/**
 * 合并Cookie信息
 * @param     $ck1   原始cookie
 * @param     $ck2   新的cookie
 * @return    合并的Cookie信息
*/
function mergeCookie($ck1 ,$ck2) {
    $ck1 = str_replace("; ", ";", $ck1);
    $ck2 = str_replace("; ", ";", $ck2);
    
    $ck3 = [];
    
    $del = ["SameSite","Path","Domain","Expires","PATH","EXPIRES","DOMAIN"];
    $is = false;
    
    // 将第一个cookie分割成数组
    foreach (explode(";",$ck1) as $val){
        if(strpos($val, "=")){
            $obj = explode("=",$val);
            foreach ($del as $v){
                if($obj[0] == $v) $is=true;
            }
            if(!$is and !empty($obj[1])){
                if($obj[1]){
                    $ck3[$obj[0]]=$obj[1];
                }
            }
        }
    }
    // 将第一个cookie分割成数组
    foreach (explode(";",$ck2) as $val){
        if(strpos($val, "=")){
            $obj = explode("=",$val);
            foreach ($del as $v){
                if($obj[0] == $v) $is=true;
            }
            
            if(!$is and !empty($obj[1])){
                $ck3[$obj[0]]=$obj[1];
            }
        }
    }
    
    $Cookie = null;
    foreach ($ck3 as $key=>$val){
        if($val!="deleted"){
            $Cookie .= $key."=".$val."; ";
        }
    }
    return substr($Cookie, 0, -2);
}

?>