<?php
/**
 * 功能函数
 * User: yanue
 * Date: 11/12/13
 * Time: 9:36 AM
 */

namespace App\Admin\Lib;


class Func {

    /**
     * 格式化时间
     *
     * @param $time
     * @return bool|string
     */
    static function formatTime($time) {
        $rtime = date("Y-m-d H:i",$time);
        $htime = date("H:i",$time);
        $time = time() - $time;
        if ($time < 60)
        {
            $str = '刚刚';
        }elseif($time < 60 * 60)
        {
            $min = floor($time/60);
            $str = $min.'分钟前';
        }elseif($time < 60 * 60 * 24)
        {
            $h = floor($time/(60*60));
            $str = $h.'小时前 '.$htime;
        }elseif($time < 60 * 60 * 24 * 3)
        {
            $d = floor($time/(60*60*24));
            if($d==1)
                $str = '昨天 '.$htime;
            else
                $str = '前天 '.$htime;
        }else
        {
            $str = $rtime;
        }
        return $str;
    }

} 