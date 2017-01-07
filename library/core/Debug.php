<?php
namespace Library\Core;

if (!defined('LIB_PATH')) exit('No direct script access allowed');
defined('FILE_WRITE_MODE') || define('FILE_WRITE_MODE', 0777);
/**
 * Debug 调试输出信息
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @package  lib/util
 * @time     2013-07-11
 */
class Debug
{

    private static $isInitCss = false;
    private static $requestParam = null;

    public function __construct()
    {
        self::startMemoryAndTime();
    }

    /**
     * 设置url上请求参数数组便于输出.
     * --url分发完成后设置
     *   参见来自Library/Core/Dispatcher.
     *
     * @param $params
     */
    public static function setRequestParam(& $params)
    {
        self::$requestParam = $params;
    }

    /**
     * 从当前开始监测时间和内存
     *
     */
    public static function startMemoryAndTime()
    {
        $GLOBALS['_startMemory'] = memory_get_usage();
        $GLOBALS['_startTime'] = microtime(true);
    }

    /**
     * 输出内存和时间消耗信息
     * --注意:如果需要检测某个位置,先注册 Debug::startMT() 进行检测再使用
     *
     * @return void
     */
    public static function traceMemoryAndTime()
    {
        self::css();
        $mem = memory_get_usage() - ($GLOBALS['_startMemory']);
        $time = round(microtime(true) - $GLOBALS['_startTime'], 6);
        $str = '<p class="trance">';
        $str .= '内存:<code>' . self::convertSize($mem) . '</code> ';
        $str .= '耗时:<code>' . $time . '</code> 秒';
        $str .= '</p>';
        echo $str;
    }

    /**
     * 默认trace的css样式
     *
     */
    private static function css()
    {

    }

    /**
     * 结构化输出信息
     *
     * @param $mixed mixed : 字串,数组..
     * @return void
     */
    public static function dump($mixed)
    {
        self::css();
        echo '<h2 fdfs="traceTitle">Dump Info:</h2>';
        echo '<pre fdfs="trane trance_array">';
        echo var_export($mixed);
        echo '</pre>';
        self::traceMemoryAndTime();
    }

    /***
     * 分段运行时间
     * --说明: 打印代码执行的行数.以及执行花费时间.
     *
     * @return void
     */
    public static function runtime()
    {
        self::css();
        $debug = debug_backtrace();
        $du = round((microtime(true) - $GLOBALS['_startTime']), 6);
        $mem = memory_get_usage() - ($GLOBALS['_startMemory']);
        //打印代码执行的行数.以及执行花费时间.
        echo '<p class="trance"><code>File:</code>' . $debug[0]['file'] . ' | <code>Line</code>:' . $debug[0] ['line'] .
            '  | <code>' . $du . '</code> min ' . self::convertSize($mem) . '</p>';
    }

    /**
     * 输出代码追踪信息
     *
     * @return void
     */
    public static function trace()
    {
        self::css();
        echo '<h2 fdfs="traceTitle">Stack trace :</h2>';
        echo '<pre class="trance">';
        echo debug_print_backtrace();
        echo '</pre>';
        echo '<h2 fdfs="traceTitle">Request Parameters :</h2>';
        echo '<pre class="trance">';
        echo var_export(self::$requestParam);
        echo '</pre>';
    }

    /**
     *  字节转换
     *
     * @param $size
     * @return string
     */
    private static function convertSize($size)
    {
        $unit = array('byte', 'kb', 'mb', 'gb', 'tb', 'pb');
        return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    public static function log($msg, $level = 'INFO')
    {
        $level = strtoupper($level);

        $filepath = WEB_ROOT . '/log/' . date('Y-m-d') . '/log-' . date('H') . '.log';
        $message = '';
        $base = dirname($filepath);
        if (!is_dir($base)) {
            mkdir($base, 0777, true);
        }

        if (!file_exists($filepath)) {
            $newfile = TRUE;
        }

        if (!$fp = fopen($filepath, 'a+')) {
            return FALSE;
        }


        $message .= $level . ' ' . ($level === 'INFO' ? ' -' : '-') . ' ' . date('Y-m-d H:i:s') . ' --> ' . $msg . "\n";

        flock($fp, LOCK_EX);
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);

        if (isset($newfile) && $newfile === TRUE) {
            @chmod($filepath, FILE_WRITE_MODE);
        }

        return TRUE;
    }

}