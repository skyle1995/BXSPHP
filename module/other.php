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

$sql_tables = "pre_config";

$conf_db = M($sql_tables);

// 通过数据库判断是否安装
if (count($conf_db->SQL("show tables like '{$sql_tables}'")) == 0) {
    sysmsg("未找到数据表，请先将sql文件导入到数据库！","系统提醒");
}else{
    $row=$conf_db->select();$conf=array();
    if(is_array($row)){
        foreach ($row as $key=>$val) {
            $conf[$val["k"]] = $val["v"];
        }
    }else{
        sysmsg("未找到数据内容，请检查数据表信息！","系统提醒");
    }
};

define('SYSKEY', $conf['syskey']); // 登录信息混淆密钥
$password_hash = '!@#%!s!0'; // 哈希值附加串 默认即可

$host = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST']; // 用户访问的地址
$time = time(); // 当前时间戳
$date = date("Y-m-d H:i:s",$time); // 当前时间
$real_ip = real_ip(); // 用户ip地址
?>
