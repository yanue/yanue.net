<?php
/**
 * ShortMessage.php
 *
 * @author    nongquan
 * @time     2013-12-14 下午2:28
 */

namespace Library\Util;

/**
 * Class Sms 短信发送类
 */
class ShortMessage {

    /**
     * 类属性
     *
     * @var string $user_name 短信API用户名
     * @var string $pass_word 短信API密码
     */
    private $user_name = 'SDK-HGG-010-00044';
    private $pass_word = '6FEA2600BDF27B7C387B398632BFDF97'; // 处理后的密钥

    /**
     * 构造函数
     *
     */
    public function __construct() {}

    /**
     * 发送自定义短信
     *
     * @param string $phone 手机号码，多个以逗号','隔开
     * @param string $message 自定义短信内容
     * @return array
     */
    public function send($phone, $message) {
        /* 验证参数START */
        if (!Validator::validateNumeric($phone) || !Validator::validateLength($phone, 10, 12) || !Validator::validateCellPhone($phone)) {
            return array(
                'error' => array(
                    'code' => 11111,
                    'msg' => '号码格式不正确',
                    'desc' => '号码格式不合法'
                ),
                'result' => 0
            );
        }
        if (empty($message)) {
            //内容为空
            return array(
                'error' => array(
                    'code' => 22222,
                    'msg' => '内容不能为空',
                    'desc' => '内容不能为空'
                ),
                'result' => 0
            );
        }
        /* 验证参数END */

        $flag = 0;
        // 要post的数据
        $argv = array(
            'sn'        => $this->user_name, // 序列号
            'pwd'       => $this->pass_word, //密码
            'mobile'    => $phone, // 手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
            'content'   => urlencode($message), // 短信内容
            'ext'       => '',
            'rrid'      => '', // 默认空 如果空返回系统生成的标识串 如果传值保证值唯一 成功则返回传入的值
            'stime'     => '' // 定时时间 格式为2011-6-29 11:09:21
        );
        // 构造要post的字符串
        $params = '';
        foreach ($argv as $key => $value) {
            if ($flag != 0) {
                $params .= "&";
                $flag = 1;
            }
            $params .= $key . "=";
            $params .= urlencode($value);
            $flag = 1;
        }
        $length = strlen($params);
        // 创建socket连接
        $fp = fsockopen("sdk2.entinfo.cn", 8060, $errno, $errstr, 10) or exit($errstr . "--->" . $errno);
        // 构造post请求的头
        $header = "POST /webservice.asmx/mdSmsSend_u HTTP/1.1\r\n";
        $header .= "Host:sdk2.entinfo.cn\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . $length . "\r\n";
        $header .= "Connection: Close\r\n\r\n";
        // 添加post的字符串
        $header .= $params . "\r\n";
        // 发送post的数据
        fputs($fp, $header);
        $inheader = 1;
        $line = '';
        while (!feof($fp)) {
            $line = fgets($fp, 1024); //去除请求包的头只显示页面的返回数据
            if ($inheader && ($line == "\n" || $line == "\r\n")) {
                $inheader = 0;
            }
            if ($inheader == 0) {
                // echo $line;
            }
        }
        // xml转数组
        $xml = simplexml_load_string($line);
        $mixArray = (array)$xml;
        if ($mixArray[0] >= 0) { // 发送成功
            return array(
                'error' => array(
                    'code' => 0,
                    'msg' => '成功',
                    'desc' => '短信发送成功'
                ),
                'result' => 1,
                'data' => array(
                    'rr' => $mixArray[0]
                )
            );
        } else { // 发送失败
            return array(
                'error' => array(
                    'code' => 33333,
                    'msg' => '失败',
                    'desc' => '短信发送失败'
                ),
                'result' => 0,
                'data' => array(
                    'ec' => $mixArray[0]
                )
            );
        }
    }
}