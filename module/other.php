<?php
// 这个文件必须在db库之后加载，否则报错

//过滤提交数据
if (!get_magic_quotes_gpc() && $sql_check==true)
{
    if (!empty($_GET))
    {
        $_GET  = addslashes_deep($_GET);
    }
    if (!empty($_POST))
    {
        $_POST = addslashes_deep($_POST);
    }
    $_COOKIE   = addslashes_deep($_COOKIE);
    $_REQUEST  = addslashes_deep($_REQUEST);
}

//配置数据库信息
define('SQL_DBMS', $config['sql_int']['dbms']);
define('SQL_HOST', $config['sql_int']['host']);
define('SQL_NAME', $config['sql_int']['name']);
define('SQL_PORT', $config['sql_int']['port']);
define('SQL_USER', $config['sql_int']['user']);
define('SQL_PASS', $config['sql_int']['pass']);
define('SQL_CHARSET', $config['sql_int']['charset']);

if(!SQL_USER||!SQL_PASS||!SQL_NAME) sysmsg("请先配置config.php中的数据库信息！","系统提醒"); // 通过配置判断是否安装

$conf_db = M("pre_config");

// 通过数据库判断是否安装
if (count($conf_db->SQL("show tables like 'pre_config'")) == 0) {
    sysmsg("请先将sql文件导入到数据库！","系统提醒");
}else{
    $row=$conf_db->select();$conf=array();
    foreach ($row as $key=>$val) {
        $conf[$val["k"]] = $val["v"];
    }
};

$site = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST']; //访问地址
$time = time();
$date = date("Y-m-d H:i:s",$time);
$password_hash = '!@#%!s!0';
$real_ip = real_ip();
?>
