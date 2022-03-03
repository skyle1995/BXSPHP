<?php
/**
 * 除去数组中的空值和签名参数
 * @param $para 签名参数组
 * return 去掉空值与签名参数后的新签名参数组
 */
function paraFilter($para) {
    $para_filter = array();
    foreach ($para as $key=>$val) {
        if($key == "sign" || $key == "sign_type" || $val == "")continue;
        else $para_filter[$key] = $para[$key];
    }
    return $para_filter;
}

/**
 * 对数组排序
 * @param $para 排序前的数组
 * return 排序后的数组
 */
function argSort($para) {
    ksort($para);
    reset($para);
    return $para;
}

/**
 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
 * @param $para 需要拼接的数组
 * return 拼接完成以后的字符串
 */
function createLinkstring($para) {
    $arg    = "";
    foreach ($para as $key=>$val) {
        $arg.=$key."=".$val."&";
    }
    //去掉最后一个&字符
    $arg = substr($arg,0,-1);

    return $arg;
}

/**
 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
 * @param $para 需要拼接的数组
 * return 拼接完成以后的字符串
 */
function createLinkstringUrlencode($para) {
    $arg    = "";
    foreach ($para as $key=>$val) {
        $arg.=$key."=".urlencode($val)."&";
    }
    //去掉最后一个&字符
    $arg = substr($arg,0,-1);

    return $arg;
}

/**
 * 签名字符串
 * @param $prestr 需要签名的字符串
 * @param $key 私钥
 * return 签名结果
 */
function md5Sign($prestr, $signkey) {
    $prestr = $prestr . $signkey;
    return md5($prestr);
}

/**
 * 验证签名
 * @param $prestr 需要签名的字符串
 * @param $sign 签名结果
 * @param $key 私钥
 * return 签名结果
 */
function md5Verify($prestr, $sign, $signkey) {
    $prestr = $prestr . $signkey;
    $mysgin = md5($prestr);

    if($mysgin == $sign) {
        return true;
    }
    else {
        return false;
    }
}


/**
 * 校验签名数据 API接口专用
 * @param $queryArr     提交的参数数据
 * @param $apipid       用户PID
 * @param $apikey       用户KEY
 */
function signVerify($queryArr,$apipid,$apikey){
    $prestr = createLinkstring(argSort(paraFilter($queryArr))); // 将数组还原成字符串
    $pid=daddslashes($queryArr['pid']);
    if(empty($pid)) exit(json_str(array("code"=>"-1","msg"=>"PID信息未找到")));
    if($pid != $apipid) exit(json_str(array("code"=>"-1","msg"=>"PID信息不匹配")));
    // exit(md5Sign($prestr,$conf['api_key']));
    if(!md5Verify($prestr, $queryArr['sign'], $apikey)) exit(json_str(array("code"=>"-1","msg"=>"签名校验失败")));
}

/**
 * 建立请求，以表单HTML形式构造（默认）
 * @param $para_temp 请求参数数组
 * @param $method 提交方式。两个值可选：post、get
 * @param $button_name 确认按钮显示文字
 * @return 提交表单HTML文本
 */
function buildRequestForm($para, $signkey, $tourl,$method, $button_name) {
    $paras=argSort(paraFilter($para)); // 去空 去签名参数 并且 重新排列数组
    $prestr=createLinkstring($paras); // 所有数组参数用&连接成字符串
    $urlstr=createLinkstringUrlencode($paras); //所有数组参数用&连接成字符串 并 Urlencode编码
    $sign=md5Sign($prestr, $signkey); // 签名字符串
    $paras['sign'] = $sign;
    $paras['sign_type'] = "MD5";
    
    $sHtml = "<html lang=\"zh-CN\">";
    $sHtml .= "<head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
    $sHtml .= "<title>$button_name</title></head><body>";
    $sHtml .= "<form id='alipaysubmit' name='alipaysubmit' action='".$tourl."' method='".$method."'>";
    while (list ($key, $val) = each ($paras)) {
        $sHtml .= "<input type='hidden' name='".$key."' value='".$val."'/>";
    }
    //submit按钮控件请不要含有name属性
    $sHtml = $sHtml."<input type='submit' value='".$button_name."'></form></body></html>";
    $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
    
    return $sHtml;
}
?>
