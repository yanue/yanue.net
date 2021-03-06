<?php
/**
 * Created by PhpStorm.
 * User: yanue-mi
 * Date: 14-1-7
 * Time: 上午10:56
 */

namespace App\Sns\Helper;


use Library\Core\Config;

class Img
{
    private static $pic_src_base_url = null;

    public static function getSmall($img_url)
    {
        return self::getImg($img_url, 's');
    }

    public static function getMiddle($img_url)
    {
        return self::getImg($img_url, 'm');
    }

    public static function getLarge($img_url)
    {
        return self::getImg($img_url, 'l');
    }

    public static function getOriginal($img_url)
    {
        return self::getImg($img_url, 'o');
    }

    private static function getImg($img_url, $size = 'o')
    {
        if ($img_url) {
            $img = parse_url($img_url, PHP_URL_PATH);
            // like /avatar/M00/00/01/wKgBqFJyG2iAfHD0AAAxCAGuAII166_s.jpg
            if (preg_match('/\/[a-z]+\/M[0-9a-zA-Z]{2}\/[0-9a-zA-Z]{2}\/[0-9a-zA-Z]{2}\/.*/i', $img)) {
                // get size avatar
                $fileInfo = pathinfo($img_url);

                $f_ext = isset($fileInfo['extension']) ? $fileInfo['extension'] : null;
                $f_dir = isset($fileInfo['dirname']) ? $fileInfo['dirname'] : null;
                $f_name = isset($fileInfo['filename']) ? $fileInfo['filename'] : null;
                # 去除slave文件名，取出原图
                $f_name = rtrim($f_name, '_l');
                $f_name = rtrim($f_name, '_m');
                $f_name = rtrim($f_name, '_s');
                if ($size == 'o' || !$size) {
                    $res = $f_dir . '/' . $f_name . '.' . $f_ext;
                } else {
                    $res = $f_dir . '/' . $f_name . '_' . $size . '.' . $f_ext;
                }
                return $res;
            } else {
                return $img_url;
            }
        }

        // if not exists
        if (!self::$pic_src_base_url) {
            self::$pic_src_base_url = 'http://' . rtrim(Config::getItem('domain.src'), '/') . '/images/default/pic/';
        }

        if ($size == 'o' || !$size) {
            $res = self::$pic_src_base_url . '.png';
        } else {
            $res = self::$pic_src_base_url . 'no_pic' . '.png';
        }
        return $res;

    }
} 