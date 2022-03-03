<?php
/**
 * RC4加解密数据 如需解密，再次调用此函数即可
 * @param $data 为 加密内容
 * @param $pwd 为 私钥
 * @return cipher
*/
function rc4($data, $pwd) {
    $cipher      = '';
    $key[]       = "";
    $box[]       = "";
    $pwd_length  = strlen($pwd);
    $data_length = strlen($data);
    for ($i = 0; $i < 256; $i++) {
        $key[$i] = ord($pwd[$i % $pwd_length]);
        $box[$i] = $i;
    }
    for ($j = $i = 0; $i < 256; $i++) {
        $j       = ($j + $box[$i] + $key[$i]) % 256;
        $tmp     = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $data_length; $i++) {
        $a       = ($a + 1) % 256;
        $j       = ($j + $box[$a]) % 256;
        $tmp     = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $k       = $box[(($box[$a] + $box[$j]) % 256)];
        $cipher .= chr(ord($data[$i]) ^ $k);
    }
    return $cipher;
}

/**
 * 新_base64编码
 * @param $str 为 需要转换的文本
 * @return $str
*/
function new_enbase64($str){
    if($str == ""){
        return $str; //如果内容为空直接返回内容
    };
    $str = base64_encode($str); //加密内容
    $str = str_replace('+','-' ,$str); //文本替换
    $str = str_replace('/','_' ,$str); //文本替换
    return $str; //返回结果
}

/**
 * 新_base64解码
 * @param $str 为 需要转换的文本
 * @return $str
*/
function new_debase64($str){
    if($str == ""){
        return $str; //如果内容为空直接返回内容
    };
    $str = str_replace('-','+' ,$str); //文本替换
    $str = str_replace('_','/' ,$str); //文本替换
    $str = base64_decode($str); //加密内容
    return $str; //返回结果
}

/**
 * VIP加密算法
 * @param $str 为 原始文本
 * @param $key 为 密钥数组
 * @param $ofs 为 加密偏移
 * @return str 加密后的结果
*/
function enkay($str,$key,$ofs){
    for ($i=0; $i<strlen($str); $i++) {
        $val = ord($str{$i});
        $val = ($val + $ofs) ^ $key[$i % count($key)];
        if($val < 0){
            $val = abs($val);
            $encode .= "-";
        }
        $encode .= strtoupper(dechex($val)).",";
    }
    return base64_encode($encode); // 返回 编码后的加密结果
}

/**
 * VIP解密算法
 * @param $str 为 加密文本
 * @param $key 为 密钥数组
 * @param $ofs 为 解密偏移
 * @return str 解密后的结果
*/
function dekay($str,$key,$ofs){
    $row = explode(",",base64_decode($str)); // base64解码并分割字符串
    if(is_array($row)){
        foreach ($row as $i=>$v){
            if(substr ($v, 0,1) == "-"){
                $v = substr( $v, -(strlen($v)-1));
                $v = hexdec($v);
                $v *= -1;
            }else{
                $v = hexdec($v);
            }
            $v = ($v ^ $key[$i % sizeof($key)]) - $ofs;
            $hex .= $v.",";
            $decode .= chr($v);
        }
        return $decode; // 返回 解码后的原字符
    }else{
        return $str; // 返回 不是加密数据
    }
}


/**
 * 自动处理登录信息
 * @param $string 加密内容
 * @param $operation 处理方式
 * @param $key 密钥
 * @param $expiry 到期时间
 * @return 处理结果
*/
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    $ckey_length = 4;
    $key = md5($key ? $key : ENCRYPT_KEY);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE') {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}
?>
