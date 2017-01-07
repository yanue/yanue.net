<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 3/31/14
 * Time: 6:03 PM
 */

namespace App\Blog\Controller\Tool;


use Library\Core\Controller;


class CollectController extends Controller
{
    private $url = 'http://image.baidu.com/i?tn=baiduimage&ipn=r&ct=201326592&cl=2&lm=-1&st=-1&fm=index&fr=&sf=1&fmq=&pv=&ic=0&nc=1&z=&se=1&showtab=0&fb=0&width=&height=&face=0&istype=2&ie=utf-8&word=%E4%B9%9D%E5%AF%A8%E6%B2%9F+%E8%8A%A6%E8%8B%87%E6%B5%B7&oq=%E4%B9%9D%E5%AF%A8%E6%B2%9F+%E8%8A%A6%E8%8B%87%E6%B5%B7&rsp=-1';

    // 批量采集用户数据
    public function meet99Action()
    {
        define ('IS_PROXY', true); //是否启用代理
        /* cookie文件 */
        $cookie_file = dirname(__FILE__) . "/cgit push -u origin master

ookie_" . md5(basename(__FILE__)) . ".txt"; // 设置Cookie文件保存路径及文件名
        /*模拟浏览器*/
        $user_agent = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36";
        $res = $this->get_url($this->url);
//        print_r($_SERVER);
//        print_r($res);
        echo $res[0];
        die;
    }

    function get_url($url, $javascript_loop = 0, $timeout = 5)
    {
        $url = str_replace("&amp;", "&", urldecode(trim($url)));

        $cookie = tempnam("/tmp", "CURLCOOKIE");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); # required for https urls
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        $content = curl_exec($ch);
        $response = curl_getinfo($ch);
        curl_close($ch);

        if ($response['http_code'] == 301 || $response['http_code'] == 302) {
            ini_set("user_agent", "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36");

            if ($headers = get_headers($response['url'])) {
                foreach ($headers as $value) {
                    if (substr(strtolower($value), 0, 9) == "location:")
                        return $this->get_url(trim(substr($value, 9, strlen($value))));
                }
            }
        }

        if ((preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value)) &&
            $javascript_loop < 5
        ) {
            return $this->get_url($value[1], $javascript_loop + 1);
        } else {
            return array($content, $response);
        }
    }
}