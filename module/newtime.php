<?php
// +----------------------------------------------------------------------
// | LingDian [ WE CAN DO IT JUST LINGDIAN ]
// +----------------------------------------------------------------------
// | Copyright (c) http://i80k.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 百晓生(BXS) <skygoole88@gmail.com>
// +----------------------------------------------------------------------

// dispose Y2K38

/*********************************************************/

/**
 * 转换到时间秒数
 * @param unknown $str_time 1970-01-01 08:00:00 格式的时间
 * @return unknown|time
*/
function newTime($str_time="1970-01-01 08:00:00",$format='U'){
    $time=new DateTime($str_time);
    return $time->format($format);
}

/**
 * 转换到时间格式
 * @param $utc_value 转换的时间长度（秒）
 * @param $curformat Y-m-d H:i:s 转换格式
 * @param $zone 转换时区
 * @return unknown|date
*/
function newDate($utc_value="0",$curformat="Y-m-d H:i:s",$zone="PRC"){
    $time = new DateTime("@".$utc_value);
    $time->setTimezone(new DateTimeZone($zone));
    return $time->format($curformat);
    return $utc_value;
}
?>