<?php
/**
 * 自动实例化数据库
 * @param unknown $table
 * @return mysql_server
*/
function M($table) {
    return $Model = new DB($table);
}

/**
 * 自动翻页 
 * @param unknown $alg 总页数
 * @param unknown $page 当前页数
 * @param unknown $num 显示页数
 * @param unknown $module 附加参数
 */
function pagebar($alg, $page, $num, $module) {
    $first=1; //起始页
    $prev=$page-1; //上一页
    $next=$page+1; //下一页
    $last=$alg; //最后一页
    
    $num = min($alg, $num); // 处理显示的页码数大于总页数的情况
    if ($page > $alg || $page < 1){
        return; // 处理非法页号的情况
    }
    
    $end = $page + floor($num / 2) <= $alg ? $page + floor($num / 2) : $alg; // 计算结束页号
    $start = $end - $num + 1; // 计算开始页号
    
    if ($start < 1) { // 处理开始页号小于1的情况
        $end -= $start - 1;
        $start = 1;
    }
    
    if ($page>1)
    {
        echo '<li><a href="?page='.$first.$module.'">首页</a></li>';
        echo '<li><a href="?page='.$prev.$module.'">&laquo;</a></li>';
    } else {
        echo '<li class="disabled"><a>首页</a></li>';
        echo '<li class="disabled"><a>&laquo;</a></li>';
    }
    
    for ($i = $start; $i <= $end; $i ++) { // 输出分页条，请自行添加链接样式
        if ($i == $page){
            // echo '<li><a href="?page='.$i.$module.'">'.$i.'</a></li>';
            echo '<li class="disabled"><a>'.$i.'</a></li>';
        }else{
            echo '<li><a href="?page='.$i.$module.'">'.$i.'</a></li>';
            // echo '<li class="disabled"><a>'.$i.'</a></li>';
        }
    }
    
    if ($page<$alg) {
        echo '<li><a href="?page='.$next.$module.'">&raquo;</a></li>';
        echo '<li><a href="?page='.$last.$module.'">尾页</a></li>';
    } else {
        echo '<li class="disabled"><a>&raquo;</a></li>';
        echo '<li class="disabled"><a>尾页</a></li>';
    }
}
?>
