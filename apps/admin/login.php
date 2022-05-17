<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
    <title>验证码实例</title>
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
</head>
<body>
    <img src="/<?php echo $moduel; ?>/vcode?r=<?php=time();?>" style="float:right;border-radius:3px;" height="44" onclick="this.src='/<?php echo $moduel; ?>/vcode?r=<?php=time();?>;" title="点击更换验证码">
</body>
</html>