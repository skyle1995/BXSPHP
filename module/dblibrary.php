<?php
// +----------------------------------------------------------------------
// | LingDian [ WE CAN DO IT JUST LINGDIAN ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2019 http://i80k.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 百晓生(BXS) <skygoole88@gmail.com>
// +----------------------------------------------------------------------

// MySQL PDO insert/delete/update/select

if(!defined('IN_CORRECT')) sysmsg("系统常量未设置，请检查配置是否正常！");

class DB{
    private $server; // 数据库
    private $table; // 表名
    
    /**
     * 建立一个全新的mysql连接
     * @param unknown $config
    **/
    public function __construct($table) {
        try {
            $this->server = new PDO (SQL_DBMS.":host=".SQL_HOST.";dbname=".SQL_NAME.";charset=".SQL_CHARSET.";port=".SQL_PORT,SQL_USER,SQL_PASS);
        } catch (Exception $e) {
            sysmsg('链接数据库失败:' . $e->getMessage());
        }
        $this->server->query ( "set names " . SQL_CHARSET );
        $this->table = $table;
        if (empty ( $table )) sysmsg( '初始化数据库表失败!' );
    }
    
    /**
     * 执行原生SQL语句
     * @param unknown $query                    
    **/
    public function SQL($query) {
        $Model = $this->server->prepare ( "$query" ); // 参加预执行sql语句，pdo对象方法
        $m_exe = $Model->execute (); // 该方法为开始执行预处理sql语句
        $m_data = $Model->fetchAll ( 2 ); // 二维数组
        return array($m_exe,$m_data); // 返回该记录
    }
    
    /**
     * @author 查询记录数量
     * @param unknown $table 该参数为数据库的表名
     * @param string $where 该参数为 判断条件 例子： id=12 或者 like %哈哈%
     * @return Ambigous <>|multitype:
    **/
    public function getcount($where = '') {
        $ifwhere = '';
        
        // 加入where判断语句
        if (! empty ( $where )) {
            $ifwhere = "where {$where}";
        }
        
        $Model = $this->server->prepare ( "select count(*) as count from `{$this->table}` {$ifwhere}" ); // 参加预执行sql语句，pdo对象方法
        $Model->execute (); // 空数组，表示这里我不需要预处理的数据
        $u_data = $Model->fetchAll ( 2 ); // 二维数组
    
        if (empty ( $u_data )) {
            return false; // 返回一个空内容
        }else{
            return $u_data[0]['count']; // 返回一个二维数组
        }
    }
    
    /**
     * @author 查询记录
     * @param unknown $table 该参数为数据库的表名
     * @param string $join 联表查询，例子：left join table_b on table_a.fuck_id=table_b.id
     * @param string $where 该参数为 判断条件 例子： id=12 或者 like %哈哈%
     * @param string $desc 该参数为倒序查询，输入一个唯一的字段名，如输入 id
     * @param string $limit 该参数查询限制，从什么开始..每次查询多少记录，例子： 5,10 （从5开始查，向后查10条记录）、例子2： 10（只查10条记录出来）
     * @return Ambigous <>|multitype:
    **/
    public function select($where = '', $join = '', $desc = '', $limit = '', $sort = 'desc') {
        $ifwhere = '';
        $order = '';
        $desc_limit = '';
        
        // 加入where判断语句
        if (! empty ( $where )) {
            $ifwhere = "where {$where}";
        }
        // 倒序查询
        if (! empty ( $desc )) {
            $order = "order by {$desc} {$sort}";
        }
        // 从什么开始..每次查询多少记录
        if (! empty ( $limit )) {
            $desc_limit = "limit {$limit}";
        }
        // select * from pre_config where 1
        // SELECT * FROM `pre_config`
        // exit("SELECT * FROM `{$this->table}` {$join} {$ifwhere} {$order} {$desc_limit}");
        $Model = $this->server->prepare ( "SELECT * FROM `{$this->table}` {$join} {$ifwhere} {$order} {$desc_limit}" ); // 参加预执行sql语句，pdo对象方法
        $Model->execute (); // 空数组，表示这里我不需要预处理的数据
        $u_data = $Model->fetchAll ( 2 ); // 二维数组
    
        if (empty ( $u_data )) {
            return false; // 返回一个空内容
        }else{
            return $u_data; // 返回一个二维数组
        }
    }
        
    /**
     * 添加记录
     * @param unknown $table 该参数为数据库的表名
     * @param unknown $array 例子: array("username"=>'LingDian',"password"=>'123456');
     * @return string
    **/
    public function insert($array) {
        $into = ""; // 键名分散
        $val = array (); // 值加入到预执行
        $insert_num = 0; // 记录加入的个数
        $create_method = ''; // 预执行参数
        
        // 循环将传进来的$array分开,键名为数据库的字段名,键值为添加的数据,与键名一一对应
        foreach ( $array as $key => $value ) {
            $into .= "{$key},";
            $val [] = $value;
            $insert_num ++;
        }
    
        // 有多少个键值,就循环多少个填充问号出来填充预执行参数
        for($i = 0; $i < $insert_num; $i ++) {
            $create_method .= '?,';
        }
    
        // 去掉末尾的逗号,
        $create_method = rtrim ( $create_method, "," );
        $into = rtrim ( $into, "," );
    
        // 参加一条预执行sql语句,该方法为pdo对象
        $Model = $this->server->prepare ( "insert into `{$this->table}` ({$into}) values ({$create_method})" );
        $exe = $Model->execute ( $val ); // 该方法为开始执行预处理sql语句
        $method_id = $this->server->lastInsertId (); // 该方法为添加后该记录的id
        return $method_id; // 返回该记录id
    }
    
    /**
     * 修改记录
     * @param unknown $table 该参数为数据库的表名
     * @param unknown $array 例子: array("username"=>'LingDian',"password"=>'123456');
     * @param string $where 该参数为 判断条件 例子： id=12
     * @return boolean
    **/
    public function update($array, $where = '') {
        $data = ""; // 设置数据
        $update_data = array (); // 设置预执行的数据
        $ifwhere = ''; // where判断语句
        foreach ( $array as $key => $value ) {
            $data .= "{$key}=?,"; // 通过键名自动设置数据
            $update_data [] = $value; // 通过键值自动设置修改的数据
        }
        $data = rtrim ( $data, "," ); // 去掉右边逗号
    
        // 加入where判断语句 
        if (! empty ( $where )) {
            $ifwhere = "where {$where}";
        }
        $Model = $this->server->prepare ( "update `{$this->table}` set {$data} {$ifwhere}" ); // 参加预执行sql语句，pdo对象方法
        $exe = $Model->execute ( $update_data ); // 该方法为开始执行预处理sql语句
        return $exe; // 返回该记录id
    }
    
    /**
     * 修改记录
     * @param unknown $table 该参数为数据库的表名
     * @param unknown $array 例子: array("username"=>'bxs',"password"=>'123456');
     * @return string
    **/
    public function update2($field1,$field2,$array) {
        $into = ""; // 键名分散
        $val = array (); // 值加入到预执行
        $insert_num = 0; // 记录加入的个数
        $create_method = ''; // 预执行参数
        
        // 循环将传进来的$array分开,键名为数据库的字段名,键值为添加的数据,与键名一一对应
        foreach ( $array as $key => $value ) {
            $into .= "('$key','$value'),";
            $val [] = $value;
            $insert_num ++;
        }
        
        // 有多少个键值,就循环多少个填充问号出来填充预执行参数
        for($i = 0; $i < $insert_num; $i ++) {
            $create_method .= '?,';
        }
        
        // 去掉末尾的逗号,
        $create_method = rtrim ( $create_method, "," );
        $into = rtrim ( $into, "," );
        
        //return "insert into `{$this->table}` ($field1,$field2) values $into on duplicate key update $field2=values($field2)";
        
        // 参加一条预执行sql语句,该方法为pdo对象
        $Model = $this->server->prepare ( "insert into `{$this->table}` ($field1,$field2) values $into on duplicate key update $field2=values($field2)" );
        $m_data = $Model->execute (); // 该方法为开始执行预处理sql语句
        return $m_data; // 返回该记录数组
    }
    
    /**
     * 删除记录
     * @param unknown $table 该参数为数据库的表名
     * @param unknown $where 该参数为 判断条件 例子： id=12 ,那么id是12的这行记录将会被删除, 如果不传$where条件，那么整个表的数据全部都会删除
     * @return boolean
    **/
    public function delete($where = '') {
        // 加入where判断语句
        if (! empty ( $where )) {
            $ifwhere = "where {$where}";
        }
        $del = $this->server->exec ( "DELETE FROM `{$this->table}` {$ifwhere}" ); // 空数组，表示这里我不需要预处理的数据
        return $del; // 返回删除是否成功
    }
    
    /**
     * 开启事务         
    **/
    public function openTransaction() {
        $this->server->beginTransaction ();
    }
    
    /**
     * 提交事务        
    **/
    public function commit() {
        $this->server->commit ();
    }
    
    /**
     * 回滚事务
    **/
    public function rollBack() {
        $this->server->rollBack ();
    }
    
    /**
     * 关闭连接
    **/
    public function __destruct() {
        $this->server = null; 
    }
}

/**
 * 自动实例化数据库
 * @param unknown $table
 * @return mysql_server
*/
function M($table) {
    return $Model = new DB($table);
}

// //SQLite 数据库处理
// class DB {
//     var $link = null;
    
//     function __construct($db_file){
//         global $siteurl;
//         $this->link = new PDO('sqlite:'.ROOT.'includes/SQLite/'.$db_file.'.db');
//         if (!$this->link) die('Connection Sqlite failed.\n');
//         return true;
//     }
    
//     function fetch($q){
//         return $q->fetch();
//     }
//     function get_row($q){
//         $sth = $this->link->query($q);
//         return $sth->fetch();
//     }
//     function count($q){
//         $sth = $this->link->query($q);
//         return $sth->fetchColumn();
//     }
//     function query($q){
//         return $this->result=$this->link->query($q);
//     }
//     function affected(){
//         return $this->result->rowCount();
//     }
//     function error(){
//         $error = $this->link->errorInfo();
//         return '['.$error[1].'] '.$error[2];
//     }
// }
?>