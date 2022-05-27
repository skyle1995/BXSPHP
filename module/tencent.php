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

// QQ登录模块（需要同时开启 curl访问处理模块）

// 110646707 和平精英
// 100383922 腾讯新闻
// 101483052 腾讯视屏
// 100497308 QQ音乐
// 100686853 QQ阅读
// 101777919 腾讯开放平台

/**
 * QQ登录_扫码Token
 * @param     $qrsig   qrsig
 * @return    ptqrtoken
*/
function QRToken($qrsig) {
    $hash = 0;
    for($i=0;$i<strlen($qrsig);$i++){
        $comp = (($hash << 5) & 0xffffffff);
        $hash = $hash + $comp;
        $hash = $hash + ord($qrsig[$i]);
    }
    $hash = ($hash & 2147483647);
    return $hash;
}

/**
 * QQ空间_获取登录二维码
 * @return    二维码信息数组
*/
function QR_img($aid) {
    $domain = "https://xui.ptlogin2.qq.com/cgi-bin/xlogin?appid=716027609&pt_3rd_aid={$aid}&daid=381&s_url=http%3A%2F%2Fconnect.qq.com";
    $ret = (string)curl_senior($domain,"GET",0,0,0,1,1);
    $val =  explode("\r\n\r\n",$ret,2);
    $ck1 = getHeader($val[0],"Set-Cookie");

    $domain = "https://ssl.ptlogin2.qq.com/ptqrshow?appid=716027609&e=2&l=M&s=3&d=72&v=4&daid=383&pt_3rd_aid={$aid}&t=0.".randomFloat(0,1,"16");
     
    $ret = (string)curl_senior($domain,"GET",0,$ck1,0,1);
    $val =  explode("\r\n\r\n",$ret,2);
    $ck2 = getHeader($val[0],"Set-Cookie");
    
    $cookie = mergeCookie($ck1 ,$ck2);
    $qrsig = getCookie($cookie,"qrsig");
    $login_sig = getCookie($cookie,"pt_login_sig");
    
    $base64 = base64_encode($val[1]);
    
    return ["login_sig"=>$login_sig,"qrsig"=>$qrsig,"qrimg"=>$base64];
}

/**
 * QQ登录_获取二维码状态
 * @param     $qrsig    qrsig
 * @return    二维码状态信息
*/
function QR_info($aid,$qrsig,$login_sig) {
    $token = QRToken($qrsig);
    $skip = "https%3A%2F%2Fgraph.qq.com%2Foauth2.0%2Flogin_jump";
    $domain = "https://ssl.ptlogin2.qq.com/ptqrlogin?aid=716027609&daid=383&from_ui=1&pt_3rd_aid={$aid}&ptqrtoken={$token}&login_sig={$login_sig}&u1={$skip}&action=0-0-".randomFloat(0,1,"16");
    $ret = (string)curl_senior($domain,"GET",0,"qrsig=".$qrsig);
    return $ret;
}
?>