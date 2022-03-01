<?php
// 设置加密密钥
define('SYS_KEY', $conf['syskey']);

if(isset($_COOKIE["admin_token"])){
    $session=md5($conf['admin_user'].$conf['admin_pwd'].$password_hash);
    $admin_token=authcode(daddslashes($_COOKIE['admin_token']), 'DECODE', SYS_KEY);
    list($user, $sid, $expiretime) = explode("\t", $admin_token);
    if($user == $conf['admin_user'] && $session==$sid && $expiretime>time()) {
        $expiretime=time() + 604800;
        $token=authcode("{$user}\t{$session}\t{$expiretime}", 'ENCODE', SYS_KEY);
        setcookie("admin_token", $token, time() + 604800);
        $islogin=1;
    }
}
?>