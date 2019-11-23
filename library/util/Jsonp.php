<?php
namespace Library\Util;

use Library\Core\Config;

/**
 * ajax output handle for web api
 *
 * @author     yanue <yanue@outlook.com>

 * @package  lib/util
 * @time     2013-07-11
 */
class Jsonp
{

    /**
     * error msg for defined code
     *
     * @source 参见当前module下configs/errorCode.php
     * @var null
     */
    public static $errmsg = null;


    public function __construct()
    {
        self::$errmsg = Config::getSite('errcode');
    }

    /**
     * 输出操作成功后信息
     *  ----解决中文unicode显示问题
     * @param string $msg 提示信息
     * @param string $data 返回数据
     */
    public static function outRight($msg = '', $data = '')
    {
        self::setHead();
        $msg = $msg ? $msg : '操作成功';
        $result = array(
            'error' => array('code' => 0, 'msg' => $msg, 'more' => ''),
            'result' => 1,
            'data' => $data
        );
        echo $_REQUEST["callback"] . '(' . json_encode($result, JSON_UNESCAPED_UNICODE) . ')'; // php 5.4
        exit;
    }

    public static function jsonResult(array $data)
    {
        self::setHead();
        echo $_REQUEST["callback"] . '(' . json_encode($data, JSON_UNESCAPED_UNICODE) . ')'; // php 5.4
        exit;
    }

    /**
     * echo error json data
     *
     * @param $code
     * @param string $msg
     * @param bool $exit
     */
    public static function outError($code, $msg = '', $exit = true)
    {
        self::setHead();
        $result = array(
            'error' => array('code' => $code, 'msg' => self::getErrorMsg($code), 'more' => $msg),
            'result' => 0,
        );
        echo $_REQUEST["callback"] . '(' . json_encode($result, JSON_UNESCAPED_UNICODE) . ')';
        if ($exit) exit;
    }

    /**
     * get error msg by defined code
     * @param $code
     * @return string
     */
    public static function getErrorMsg($code)
    {
        if (!self::$errmsg) {
            self::$errmsg = Config::getSite('errcode');
        }
        return isset($code) && isset(self::$errmsg[$code]) ? self::$errmsg[$code] : '';
    }

    /**
     * 设置ajax跨域head
     */
    public static function setHead()
    {
        header("content-type: text/javascript; charset=utf-8");
        header("Access-Control-Allow-Origin: *"); # 跨域处理
        header("Access-Control-Allow-Headers: content-disposition, origin, content-type, accept");
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET, POST');

        // Make sure file is not cached (as it happens for example on iOS devices)
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }
}