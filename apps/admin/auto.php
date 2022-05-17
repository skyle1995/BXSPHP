<?php
// 判断登录信息
if(isset($_COOKIE["admin_token"])){
    $admin_token = (string)authcode(daddslashes($_COOKIE['admin_token']), 'DECODE', LOGINS_HASH);
    list($admin_user, $sid, $expiretime) = explode("\t", $admin_token);
    
    $session=md5($conf['admin_user'].$conf['admin_pwd'].ENCRYPT_KEY);
    if($admin_user == $conf['admin_user'] && $session==$sid && $expiretime>$time) {
        $expiretime=$time + $system["excookie"];
        $admintoken=authcode("{$admin_user}\t{$session}\t{$expiretime}", 'ENCODE', LOGINS_HASH);
        setcookie("admin_token", $admintoken, $expiretime,"/".$moduel);
        $islogin=1;
    }
}
?>
