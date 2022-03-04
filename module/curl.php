<?php
/**
 * 访问网页 高级
 * 参数为0则默认或不开启 1为启用
 * @param     $url          网页地址
 * @param     $post         需要提交的POST数据（可自定义）。
 * @param     $httpheader   需要提交的header数据（可自定义）。
 * @param     $cookie       在HTTP请求中提交的cookie（可自定义）。
 * @param     $referer      在HTTP请求头中"Referer:"的内容（可自定义）。
 * @param     $ua           在HTTP请求中提交的ua信息（可自定义）。
 * @param     $header       启用时会将头文件的信息作为数据流输出。
 * @param     $nobaody      启用时将不对HTML中的body部分进行输出。
 * @return    获取到的内容
*/
function senior_curl($url, $post=0, $httpheader=0, $cookie=0, $referer=0, $ua=0, $header=0, $nobaody=0) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if(!$httpheader or $httpheader == 0){
        $httpheader[] = "Accept:*/*";
        $httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
        $httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
        $httpheader[] = "Connection:close"; 
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    if($post){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    if($header){
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
    }
    if($cookie){
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    if($referer){
        if($referer==1){
            curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
        }else{
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }
    }
    if($ua){
        curl_setopt($ch, CURLOPT_USERAGENT,$ua);
    }else{
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Linux; Android 4.4.2; NoxW Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Mobile Safari/537.36');
    }
    if($nobaody){
        curl_setopt($ch, CURLOPT_NOBODY,1);
    }
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $ret = curl_exec($ch);
    curl_close($ch);
    return $ret;
}


/**
 * 访问网页 GET
 * @param     $url  网页地址
 * @return          获取到的内容
*/
function curl_get($url){  
    $curl = $url;  
    $ch = curl_init();    
    curl_setopt($ch, CURLOPT_URL, $curl);    
    //参数为1表示传输数据，为0表示直接输出显示。  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
    //参数为0表示不带头文件，为1表示带头文件  
    curl_setopt($ch, CURLOPT_HEADER,0);  
    
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//绕过ssl验证
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '0');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '0');
    
    $output = curl_exec($ch);   
    curl_close($ch);   
    return $output;  
}

/**
 * 访问网页 POST
 * @param     $url      网页地址
 * @param     $post     需要提交的POST数据（可自定义）。
 * @return              获取到的内容
*/
function curl_post($url,$post=0){  
    $curl = curl_init();  
    //设置提交的url  
    curl_setopt($curl, CURLOPT_URL, $url);  
    //设置头文件的信息作为数据流输出  
    curl_setopt($curl, CURLOPT_HEADER, 0);  
    //设置获取的信息以文件流的形式返回，而不是直接输出。  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
    if($post){
        //设置post方式提交  
        curl_setopt($curl, CURLOPT_POST, 1);  
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);  
    }
    //执行命令  
    $data = curl_exec($curl);  
    //关闭URL请求  
    curl_close($curl);  
  //获得数据并返回  
    return $data;  
}
?>